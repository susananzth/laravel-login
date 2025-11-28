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
     * Constructor que recibe el contexto.
     * @param bool $isWelcome True si es un registro nuevo, False si solo cambió el email en perfil.
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
        // Genera URL firmada temporal. Si alguien altera un solo caracter de la URL, Laravel la rechaza.
        $url = $this->verificationUrl($notifiable);

        // Lógica condicional para enviar plantillas de correo diferentes
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
