<div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

    <x-auth-header
        :title="__('Iniciar Sesión')"
        :description="__('Ingresa a tu cuenta para gestionar tus citas')"
    />

    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="space-y-6">
        <x-forms.input
            name="email"
            wireModel="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="tu@email.com"
        />

        <x-forms.input
            name="password"
            wireModel="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="current-password"
            :placeholder="__('Ingresa tu contraseña')"
            viewable
        />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <x-checkbox
                wireModel="remember"
                :label="__('Recordar sesión')"
            />

            @if (Route::has('password.request'))
                <a class="text-sm text-moto-red hover:text-red-700 font-medium"
                    href="{{ route('password.request') }}"
                    wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <x-button variant="primary" type="submit" class="w-full">
            {{ __('Iniciar Sesión') }}
        </x-button>
    </form>

    @if (Route::has('register'))
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                {{ __('¿No tienes una cuenta?') }}
                <a href="{{ route('register') }}"
                    class="text-moto-red hover:text-red-700 font-medium"
                    wire:navigate>
                    {{ __('Regístrate aquí') }}
                </a>
            </p>
        </div>
    @endif
</div>
