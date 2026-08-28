<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\ZoneState;
use App\Models\LocalGovernment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use App\Mail\UserRegistrationWelcomeMail;
use App\Mail\AdminNewApplicantMail;

class RegisterPortal extends Component
{
    use WithFileUploads;

    public $name, $email, $password, $phone;
    public $registration_role = 'volunteer';

    public $selected_state_id = '';
    public $selected_lga_id = '';

    public $organization_name;
    public $qualification_degree;
    public $skills_and_expertise;
    public $motivation_statement;
    public $document;
    public $profile_photo;
    public bool $privacy_policy_accepted = false;
    public bool $terms_accepted = false;

    public $recaptcha_token; // Google reCAPTCHA token property

    public function mount()
    {
        if (request()->has('role')) {
            $this->registration_role = request()->get('role');
        }
    }

    public function updatedSelectedStateId()
    {
        $this->selected_lga_id = '';
    }

    public function register()
    {
        // 1. Standard Validation Rules
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'registration_role' => 'required|in:volunteer,coordinator,partner,student',
            'selected_state_id' => 'required|exists:zone_states,id',
            'selected_lga_id' => 'required|exists:local_governments,id',
            'motivation_statement' => 'required|string|min:10',
            'document' => $this->registration_role === 'partner'
                ? 'nullable|file|mimes:pdf,doc,docx|max:5120'
                : 'required|file|mimes:pdf,doc,docx|max:5120',
            'profile_photo' => 'nullable|image|max:2048',
            'privacy_policy_accepted' => 'accepted',
            'terms_accepted' => 'accepted',
            'recaptcha_token' => 'required',
        ], [
            'recaptcha_token.required' => 'Please complete the reCAPTCHA bot verification challenge.',
            'privacy_policy_accepted.accepted' => 'You must accept the Privacy Policy to continue.',
            'terms_accepted.accepted' => 'You must accept the Terms and Conditions to continue.',
        ]);

        // 2. Google reCAPTCHA Verification
        $recaptchaSecret = config('captcha.secret');
        if ($recaptchaSecret) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $this->recaptcha_token,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->json('success')) {
                $this->addError('recaptcha_token', 'reCAPTCHA verification failed. Please try again.');
                return;
            }
        }

        $filePath = null;
        if ($this->document) {
            $filePath = $this->document->store('applicant_documents', 'public');
        }

        $profilePhotoPath = $this->profile_photo
            ? $this->profile_photo->store('profile_photos', 'public')
            : null;

        $lgaModel = LocalGovernment::find($this->selected_lga_id);

        // Readable role map
        $roleTitleMap = [
            'volunteer' => 'Volunteer',
            'coordinator' => 'Coordinator',
            'partner' => 'Partner / Donor',
            'student' => 'Student',
        ];

        $roleTitle = $roleTitleMap[$this->registration_role] ?? 'Volunteer';

        // 3. Create User
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'user_type' => 'staff',
            'registration_role' => $roleTitle,
            'zone_id' => $lgaModel->state->zone_id ?? null,
            'zone_state_id' => $this->selected_state_id,
            'local_government_id' => $this->selected_lga_id,
            'organization_name' => $this->organization_name,
            'qualification_degree' => $this->qualification_degree,
            'skills_and_expertise' => $this->skills_and_expertise,
            'motivation_statement' => $this->motivation_statement,
            'document_path' => $filePath,
            'profile_photo_path' => $profilePhotoPath,
            'privacy_policy_accepted' => true,
            'privacy_policy_accepted_at' => now(),
            'terms_accepted' => true,
            'terms_accepted_at' => now(),
            'application_status' => 'pending',
            'is_active' => false,
        ]);

        // 4. Assign Spatie Role
        Role::firstOrCreate(['name' => $roleTitle]);
        $user->assignRole($roleTitle);

        // 5. Email notifications — failures here must never block a successful
        // registration, so they're isolated and logged rather than thrown.
        try {
            Mail::to($user->email)->send(new UserRegistrationWelcomeMail($user->name, $roleTitle));
        } catch (\Throwable $e) {
            Log::error('Failed to send registration confirmation email.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            $adminEmails = User::role('Super Admin')->pluck('email');
            if ($adminEmails->isNotEmpty()) {
                Mail::to($adminEmails->first())
                    ->cc($adminEmails->slice(1))
                    ->send(new AdminNewApplicantMail($user, $roleTitle));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send new-applicant notification to admins.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        session()->flash('message', 'Registration submitted successfully! Applied Role: ' . $roleTitle . '. Check your email for confirmation — we\'ll notify you once an administrator reviews your application.');

        return redirect()->route('login');
    }

    public function render()
    {
        $states = ZoneState::orderBy('name', 'asc')->get();

        $lgas = $this->selected_state_id 
            ? LocalGovernment::where('zone_state_id', $this->selected_state_id)->orderBy('name', 'asc')->get() 
            : collect();

        return view('livewire.public.register-portal', [
            'states' => $states,
            'lgas' => $lgas,
        ])->layout('layouts.guest');
    }
}