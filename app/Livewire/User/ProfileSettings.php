<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileSettings extends Component
{
    // Profile Details Fields
    public $name, $email, $phone;

    // Password Update Fields
    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('profile_message', 'Profile details updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password you provided is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_message', 'Password changed successfully! Please use your new password for future logins.');
    }

    public function render()
    {
        return view('livewire.user.profile-settings', [
            'user' => auth()->user(),
        ])->layout('layouts.app');
    }
}