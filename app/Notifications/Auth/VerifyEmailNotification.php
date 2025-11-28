<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public $isWelcome; // Variable para saber el contexto

    /**
     * Recibimos si es bienvenida (true) o solo cambio de correo (false)
     */
    public function __construct($isWelcome = false)
    {
        $this->isWelcome = $isWelcome;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Lógica nativa de Laravel para generar la URL firmada segura
        $url = $this->verificationUrl($notifiable);

        // CASO 1: Registro Nuevo (Bienvenida)
        if ($this->isWelcome) {
            return (new MailMessage)
                ->subject('¡Bienvenido a MotoRápido! - Confirma tu correo')
                ->view('emails.auth.verify-email', ['url' => $url, 'user' => $notifiable]);
        }

        // CASO 2: Cambio de Correo (Solo Verificar)
        return (new MailMessage)
            ->subject('Verifica tu nueva dirección de correo - MotoRápido')
            ->view('emails.auth.email-change-verify', ['url' => $url, 'user' => $notifiable]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
