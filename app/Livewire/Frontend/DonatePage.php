<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Donation;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\DonationReceivedMail;
use App\Mail\DonationVerifiedMail;

class DonatePage extends Component
{
    use WithFileUploads;

    public $amount = 10000;
    public $custom_amount;
    public $donor_name;
    public $donor_email;
    public $donor_phone;
    public $payment_method = 'paystack'; // 'paystack' or 'bank_transfer'
    public $notes;
    public $proof_of_payment;

    public $recaptcha_token; // Google reCAPTCHA token property
    public $terms_accepted = false;

    public $showSuccessModal = false;
    public $activeReference;

    public function selectAmount($value)
    {
        $this->amount = $value;
        $this->custom_amount = null;
    }

    public function updatedCustomAmount($value)
    {
        if ($value && is_numeric($value)) {
            $this->amount = $value;
        }
    }

    public function processDonation()
    {
        // 1. Standard Form Input Validation
        $this->validate([
            'amount' => 'required|numeric|min:500',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:paystack,bank_transfer',
            'proof_of_payment' => $this->payment_method === 'bank_transfer' ? 'required|image|max:5120' : 'nullable',
            'recaptcha_token' => 'required',
            'terms_accepted' => 'accepted',
        ], [
            'recaptcha_token.required' => 'Please complete the reCAPTCHA bot verification challenge.',
            'terms_accepted.accepted' => 'You must agree to the Donation Terms & Conditions to continue.',
        ]);

        if ($this->payment_method === 'paystack' && !config('services.paystack.public_key')) {
            $this->addError('payment_method', 'Paystack is not configured yet. Please use bank transfer or contact the site administrator.');
            return;
        }

        // 2. Server-side Google reCAPTCHA Token Verification
        $recaptchaSecret = config('captcha.secret');
        if ($recaptchaSecret) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $this->recaptcha_token,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->json('success')) {
                $this->addError('recaptcha_token', 'reCAPTCHA verification failed. Please check the box again.');
                return;
            }
        }

        $referenceCode = 'DON-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));

        $proofPath = null;
        if ($this->payment_method === 'bank_transfer' && $this->proof_of_payment) {
            $proofPath = $this->proof_of_payment->store('donation_receipts', 'public');
        }

        $donation = Donation::create([
            'reference_code' => $referenceCode,
            'donor_name' => $this->donor_name,
            'donor_email' => $this->donor_email,
            'donor_phone' => $this->donor_phone,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_method === 'paystack' ? 'pending' : 'pending_verification',
            'proof_of_payment_path' => $proofPath,
            'notes' => $this->notes,
        ]);

        Mail::to($this->donor_email)->queue(new DonationReceivedMail($donation));

        if ($this->payment_method === 'paystack') {
            // Trigger Paystack Inline JS Modal
            $this->dispatch('initiatePaystackPayment', [
                'email' => $this->donor_email,
                'amount' => $this->amount * 100, // Amount in kobo
                'ref' => $referenceCode,
                'key' => config('services.paystack.public_key'),
            ]);
        } else {
            $this->activeReference = $referenceCode;
            $this->showSuccessModal = true;
            $this->reset(['donor_name', 'donor_email', 'donor_phone', 'notes', 'proof_of_payment', 'recaptcha_token', 'terms_accepted']);
        }
    }

    #[On('verifyPaystackSuccess')]
    public function verifyPaystackSuccess($reference, $paystackRef)
    {
        $donation = Donation::where('reference_code', $reference)->first();

        if (! $donation) {
            return;
        }

        // The browser callback firing is NOT proof of payment — it can be spoofed
        // from devtools. Paystack must be asked directly whether the transaction
        // actually succeeded before we ever mark a donation as successful.
        $secretKey = config('services.paystack.secret_key');

        if (! $secretKey) {
            Log::error('Paystack secret key is not configured — cannot verify donation.', ['reference' => $reference]);
            $this->addError('payment_method', 'Payment verification is temporarily unavailable. Please try again shortly or use bank transfer.');
            return;
        }

        $response = Http::withToken($secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$paystackRef}");

        if (! $response->successful()) {
            Log::warning('Paystack verify call failed.', ['reference' => $reference, 'status' => $response->status()]);
            $this->addError('payment_method', 'We could not confirm this payment with Paystack. If you were charged, please contact us with your reference: ' . $reference);
            return;
        }

        $data = $response->json('data');

        $isGenuine = $data
            && ($data['status'] ?? null) === 'success'
            && ($data['reference'] ?? null) === $reference
            && strtolower($data['currency'] ?? '') === 'ngn'
            // Amount from Paystack is in kobo — compare against what we charged for.
            && (int) ($data['amount'] ?? 0) === (int) round($donation->amount * 100);

        if (! $isGenuine) {
            Log::warning('Paystack verification mismatch — possible spoofed callback.', [
                'reference' => $reference,
                'paystack_data' => $data,
                'expected_amount_kobo' => (int) round($donation->amount * 100),
            ]);
            $this->addError('payment_method', 'Payment could not be verified. If you were charged, please contact us with your reference: ' . $reference);
            return;
        }

        $donation->update([
            'payment_status' => 'successful',
            'paystack_reference' => $paystackRef,
        ]);

        Mail::to($donation->donor_email)->queue(new DonationVerifiedMail($donation));

        $this->activeReference = $reference;
        $this->showSuccessModal = true;
        $this->reset(['donor_name', 'donor_email', 'donor_phone', 'notes', 'recaptcha_token', 'terms_accepted']);
    }

    public function render()
    {
        $view = view()->exists('livewire.frontend.donate-page') 
            ? 'livewire.frontend.donate-page' 
            : 'frontend.donate-page';

        return view($view)->layout('layouts.guest');
    }
}