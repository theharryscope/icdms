<?php

namespace App\Livewire\User;

use Livewire\Component;

class Notifications extends Component
{
    public function markAsRead(string $notificationId): void
    {
        auth()->user()->unreadNotifications()->whereKey($notificationId)->update(['read_at' => now()]);
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->each->markAsRead();
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.user.notifications', [
            'notifications' => $user->notifications()->latest()->paginate(10),
            'unreadCount' => $user->unreadNotifications()->count(),
        ])->layout('layouts.app');
    }
}