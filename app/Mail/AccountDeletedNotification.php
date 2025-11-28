<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Correo de notificación de cuenta eliminada.
 * Implementa ShouldQueue: Esto significa que el correo no se envía al instante
 * (lo que frenaría la web), sino que se manda a una cola de trabajo en segundo plano.
 * Mejora MUCHO la experiencia del usuario.
 */
class AccountDeletedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $firstname;

    // Recibimos solo el nombre (string) y no el modelo User.
    // ¿Por qué? Porque si el User se borra de la BD antes de que la cola procese el email,
    // fallaría al intentar serializar un modelo que ya no existe (`SerializesModels`).
    public function __construct($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cuenta Eliminada - MotoRápido',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-deleted',
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
