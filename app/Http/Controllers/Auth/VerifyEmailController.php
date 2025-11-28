<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador que procesa el clic del usuario en el enlace de "Verificar Correo".
 */
class VerifyEmailController extends Controller
{
    /**
     * Marca el correo del usuario como verificado.
     * Este método se ejecuta cuando el usuario hace clic en el enlace enviado por email.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Si el usuario ya verificó su correo antes, no hacemos nada en base de datos.
        // Lo mandamos directo al dashboard.
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // Si no estaba verificado, marca la columna 'email_verified_at' con la fecha actual en la BD.
        $request->fulfill();

        // Redirige al dashboard indicando visualmente que se ha verificado (?verified=1).
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
