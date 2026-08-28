<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Donation;

class DonationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Donation Acknowledgement: ' . $this->donation->reference_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donations.received',
        );
    }
}