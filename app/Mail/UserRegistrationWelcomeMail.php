<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegistrationWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $name, public string $role) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Received — InnoTech Future Foundation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.users.welcome',
        );
    }
}