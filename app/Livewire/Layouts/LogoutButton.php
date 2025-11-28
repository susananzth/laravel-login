<?php

namespace App\Livewire\Layouts;

use App\Livewire\Actions\Logout; // Tu clase de acción existente
use Livewire\Component;

class LogoutButton extends Component
{
    /**
     * Este método se llama cuando haces clic en el botón
     */
    public function logout(Logout $logoutAction)
    {
        // 1. Ejecutamos tu lógica de cierre de sesión
        $logoutAction(); 

        // 2. Redirigimos usando navigate para que sea SPA (sin recarga completa)
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.layouts.logout-button');
    }
}