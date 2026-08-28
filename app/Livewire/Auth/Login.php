<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $recaptcha_token; // Google reCAPTCHA token property

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
        'recaptcha_token' => 'required',
    ];

    protected $messages = [
        'recaptcha_token.required' => 'Please complete the bot verification check.',
    ];

    public function login()
    {
        $this->validate();

        // Server-side Google reCAPTCHA Verification
        $recaptchaSecret = config('captcha.secret');
        if ($recaptchaSecret) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $this->recaptcha_token,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->json('success')) {
                $this->addError('recaptcha_token', 'reCAPTCHA verification failed. Please try checking the box again.');
                return;
            }
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            // An account existing and a password matching is not enough — applicants
            // must be approved (is_active) before they can access the portal. Without
            // this check, the entire admin "approve applicant" workflow was cosmetic.
            if (! $user->is_active) {
                Auth::logout();

                $message = match ($user->application_status) {
                    'rejected' => 'Your application was not approved. Please contact the InnoTech Future Foundation team for more information.',
                    default => 'Your application is still pending review by an administrator. You will be notified by email once your account is approved.',
                };

                $this->addError('email', $message);
                return;
            }

            $user->forceFill(['last_login_at' => now()])->save();

            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}