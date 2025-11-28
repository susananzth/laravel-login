<?php

namespace App\Livewire\Settings;

use App\Livewire\Actions\Logout;
use App\Mail\AccountDeletedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user = Auth::user();

        // 1. Enviamos el correo ANTES de borrar
        // Usamos try-catch para que si falla el correo, igual se borre la cuenta (seguridad primero)
        try {
            Mail::to($user->email)->send(new AccountDeletedNotification($user->firstname));
        } catch (\Exception $e) {
            Log::error("Error al enviar correo de eliminar cuenta de usuario: " . $e->getMessage());
        }

        // 2. Proceso de borrado
        tap($user, $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
