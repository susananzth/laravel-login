<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Register extends Component
{
    // Propiedades públicas para el formulario de registro
    public $firstname = '';
    public $lastname = '';
    public $username = '';
    public $phone = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = ''; // Livewire busca automáticamente esta variable para la regla 'confirmed'

    /**
     * Maneja el registro de nuevos usuarios.
     */
    public function register(): void
    {
        // 1. Validación estricta de datos.
        // 'unique:users': Verifica en la BD que no exista el username/email.
        $validated = $this->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Hash del password. NUNCA guardar contraseñas en texto plano.
        $validated['password'] = Hash::make($validated['password']);

        // 3. Crear usuario y disparar evento Registered (esto envía el email de bienvenida/verificación).
        event(new Registered(($user = User::create($validated))));

        // 4. Asignación de Rol (Librería Spatie).
        // Todos los que se registran por el formulario público son 'Cliente'.
        $user->assignRole('Cliente');

        // 5. Login automático tras registro.
        Auth::login($user);

        // 6. Redirección.
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}
