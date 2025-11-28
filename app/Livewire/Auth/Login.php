<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    // Atributos enlazados directamente al formulario con wire:model
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Procesa la solicitud de inicio de sesión.
     */
    public function login(): void
    {
        // 1. Ejecuta las validaciones definidas en los atributos #[Validate]
        $this->validate();

        // 2. Protección contra fuerza bruta: Verifica si hay demasiados intentos fallidos.
        $this->ensureIsNotRateLimited();

        // 3. Intentamos autenticar. Auth::attempt hace el hash del password y lo compara con la BD.
        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Si falla: Contamos un intento fallido en el RateLimiter.
            RateLimiter::hit($this->throttleKey());

            // Lanzamos error de validación para mostrarlo en pantalla.
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 4. Si entra con éxito: Limpiamos el contador de intentos fallidos.
        RateLimiter::clear($this->throttleKey());

        // 5. Regeneramos ID de sesión. OBLIGATORIO por seguridad (evita Session Fixation).
        Session::regenerate();

        // 6. Redirigimos a donde el usuario intentaba ir, o al dashboard por defecto.
        // navigate: true activa la navegación rápida de SPA de Livewire.
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Verifica que el usuario no esté bloqueado por demasiados intentos.
     * Permite máximo 5 intentos antes de bloquear.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Dispara evento de bloqueo (útil para logs de auditoría).
        event(new Lockout(request()));

        // Calcula cuánto tiempo falta para desbloquear.
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Genera una llave única para el limitador de intentos.
     * Combina el email y la IP. Así si atacan desde otra IP, no bloquean al usuario real (a menos que acierten el email).
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
