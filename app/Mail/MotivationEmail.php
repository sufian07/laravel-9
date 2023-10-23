<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MotivationEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $messageSubject, $messageBody;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($messageSubject, $messageBody)
    {
        $this->messageSubject = $messageSubject;
        $this->messageBody = $messageBody;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->messageSubject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.motivate',
            with: ['body'=> $this->messageBody],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
