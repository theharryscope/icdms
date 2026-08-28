<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Donor;
use App\Models\Grant;
use App\Models\Project;
use App\Models\Donation;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationVerifiedMail;

class GrantTracker extends Component
{
    use WithPagination;

    public $search = '';

    // Donor Form
    public $donor_name, $donor_type = 'grant_body', $donor_email, $donor_phone, $donor_country = 'Nigeria';
    public $showDonorModal = false;

    // Grant Form
    public $donor_id, $project_id, $grant_title, $total_amount, $disbursed_amount = 0, $start_date, $end_date, $description;
    public $showGrantModal = false;

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d', strtotime('+1 year'));
    }

    public function createDonor()
    {
        $this->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'nullable|email',
        ]);

        Donor::create([
            'name' => $this->donor_name,
            'donor_type' => $this->donor_type,
            'email' => $this->donor_email,
            'phone' => $this->donor_phone,
            'country' => $this->donor_country,
        ]);

        $this->reset(['donor_name', 'donor_email', 'donor_phone']);
        $this->showDonorModal = false;
        session()->flash('message', 'Donor partner registered successfully.');
    }

    public function createGrant()
    {
        $this->validate([
            'donor_id' => 'required|exists:donors,id',
            'grant_title' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:1000',
            'disbursed_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $grantCode = 'GNT-' . date('Y') . '-' . str_pad(Grant::count() + 1, 3, '0', STR_PAD_LEFT);

        Grant::create([
            'donor_id' => $this->donor_id,
            'project_id' => $this->project_id ?: null,
            'grant_title' => $this->grant_title,
            'grant_code' => $grantCode,
            'total_amount' => $this->total_amount,
            'disbursed_amount' => $this->disbursed_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description,
            'status' => 'active',
        ]);

        $this->reset(['grant_title', 'total_amount', 'disbursed_amount', 'description', 'project_id']);
        $this->showGrantModal = false;
        session()->flash('message', "Grant record created successfully! Reference Code: {$grantCode}");
    }

   public function verifyDonation($donationId)
{
    $donation = Donation::findOrFail($donationId);
    $donation->update(['payment_status' => 'successful']);

    // Trigger confirmation mail to donor
    if ($donation->donor_email) {
        Mail::to($donation->donor_email)->queue(new DonationVerifiedMail($donation));
    }

    session()->flash('message', "Donation {$donation->reference_code} verified and notification dispatched!");
}

    public function rejectDonation($donationId)
    {
        $donation = Donation::findOrFail($donationId);
        $donation->update(['payment_status' => 'rejected']);

        session()->flash('message', "Donation {$donation->reference_code} has been rejected.");
    }

    public function render()
    {
        $grants = Grant::with(['donor', 'project'])
            ->when($this->search, function ($q) {
                $q->where('grant_title', 'like', '%' . $this->search . '%')
                  ->orWhere('grant_code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $publicDonations = Donation::when($this->search, function ($q) {
                $q->where('donor_name', 'like', '%' . $this->search . '%')
                  ->orWhere('reference_code', 'like', '%' . $this->search . '%')
                  ->orWhere('donor_email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        $totalPublicDonations = Donation::where('payment_status', 'successful')->sum('amount');

        return view('livewire.admin.grant-tracker', [
            'grants' => $grants,
            'publicDonations' => $publicDonations,
            'donors' => Donor::orderBy('name')->get(),
            'projects' => Project::orderBy('title')->get(),
            'totalGrantFunding' => Grant::sum('total_amount') + $totalPublicDonations,
            'totalDisbursedFunding' => Grant::sum('disbursed_amount'),
            'totalPublicDonations' => $totalPublicDonations,
        ])->layout('layouts.app');
    }
}