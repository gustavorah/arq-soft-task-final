<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $user;
    public $inscricao;
    /**
     * Create a new message instance.
     */
    public function __construct($view, $user, $inscricao)
    {
        $this->view = $view;
        $this->user = $user;
        $this->inscricao = $inscricao;
    }

    public function build()
    {
        return $this->subject('Sistema de Eventos')
                    ->view($this->view, ['user' => $this->user, 'inscricao' => $this->inscricao]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
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
