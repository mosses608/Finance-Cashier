<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailTo;

    /**
     * Create a new message instance.
     */
    public function __construct($mailTo)
    {
        $this->mailTo = $mailTo;
    }

    public function build()
    {
        return $this->subject('Subscription Notification')
            ->view('inc.subscription')
            ->with([
                'email' => $this->mailTo,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'inc.subscription',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
