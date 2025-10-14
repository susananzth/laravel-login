<div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
    <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingrese sus datos a continuación para crear su cuenta.')" />

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="space-y-6">
        <x-forms.input
            wireModel="name"
            :label="__('Nombre')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Nombre completo')"
        />

        <x-forms.input
            wireModel="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autocomplete="email"
            placeholder="tu@email.com"
        />

        <x-forms.input
            wireModel="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Ingresa tu contraseña')"
            viewable
        />

        <x-forms.input
            wireModel="password_confirmation"
            :label="__('Confirmar contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirme contraseña')"
            viewable
        />
        <x-button variant="primary" type="submit" class="w-full">
            {{ __('Crear cuenta') }}
        </x-button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">
            {{ __('¿Ya tiene una cuenta?') }}
            <a href="{{ route('login') }}"
                class="text-moto-red hover:text-red-700 font-medium"
                wire:navigate>
                {{ __('Inicie sesión') }}
            </a>
        </p>
    </div>
</div>
