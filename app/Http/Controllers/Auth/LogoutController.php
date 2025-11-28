<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Controlador para gestionar el cierre de sesión.
 * No usamos Livewire aquí porque el logout requiere una redirección completa y
 * limpieza de cookies a nivel de respuesta HTTP estándar.
 */
class LogoutController extends Controller
{
    /**
     * Cierra la sesión del usuario actual.
     *
     * @param Request $request La solicitud HTTP actual.
     * @return \Illuminate\Http\RedirectResponse Redirige al usuario a la página de inicio.
     */
    public function __invoke(Request $request)
    {
        // 1. Cierra la sesión del guardián 'web' (usuarios normales).
        Auth::guard('web')->logout();

        // 2. Invalida la sesión actual. Esto es CRÍTICO por seguridad para prevenir
        // ataques de "Session Fixation" (que alguien use una cookie vieja para entrar).
        Session::invalidate();

        // 3. Regenera el token CSRF. Evita que formularios abiertos previamente
        // puedan enviar datos maliciosos después del logout.
        Session::regenerateToken();

        // 4. Redirección final.
        return redirect('/');
    }
}
