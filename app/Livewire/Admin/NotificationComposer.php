<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\AdminAnnouncement;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class NotificationComposer extends Component
{
    public $recipient_type = 'all';
    public $recipient_id;
    public $role_name;
    public $subject;
    public $message;

    public function send(): void
    {
        $this->validate([
            'recipient_type' => 'required|in:all,user,role',
            'recipient_id' => 'required_if:recipient_type,user|nullable|exists:users,id',
            'role_name' => 'required_if:recipient_type,role|nullable|exists:roles,name',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:5|max:10000',
        ]);

        $recipients = match ($this->recipient_type) {
            'user' => User::whereKey($this->recipient_id)->get(),
            'role' => User::role($this->role_name)->get(),
            default => User::all(),
        };

        Notification::send($recipients, new AdminAnnouncement($this->subject, $this->message));

        $this->reset(['recipient_id', 'role_name', 'subject', 'message']);
        $this->recipient_type = 'all';
        session()->flash('message', "Notification sent to {$recipients->count()} user(s).");
    }

    public function render()
    {
        return view('livewire.admin.notification-composer', [
            'users' => User::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}