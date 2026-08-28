<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminAnnouncement extends Notification
{
    public function __construct(
        public string $subject,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }

}