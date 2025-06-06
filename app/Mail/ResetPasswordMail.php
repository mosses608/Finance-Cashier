<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $token;

    /**
     * Create a new message instance.
     */

    public function __construct($username, $token)
    {
        //
        $this->username = $username;
        $this->token = $token;
    }

    public function build(){
        return $this->subject('Password Reset Request')
                    ->view('inc.reset_password')
                    ->with([
                        'username' => $this->username,
                        'resetLink' => url('/reset?token=' . $this->token . '&username=' . urlencode($this->username)),
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'inc.mail',
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
