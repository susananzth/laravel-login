<div class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingrese sus datos a continuación para crear su cuenta.')" />

        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form wire:submit="register" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input
                    name="firstname"
                    wireModel="firstname"
                    :label="__('Nombres')"
                    required
                    autofocus
                    placeholder="Ej: Juan"
                />

                <x-forms.input
                    name="lastname"
                    wireModel="lastname"
                    :label="__('Apellidos')"
                    required
                    placeholder="Ej: Pérez"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input
                    name="username"
                    wireModel="username"
                    :label="__('Usuario')"
                    required
                    placeholder="juanperez"
                />

                <x-forms.input
                    name="phone"
                    wireModel="phone"
                    :label="__('Teléfono')"
                    required
                    placeholder="999 999 999"
                />
            </div>

            <x-forms.input
                name="email"
                wireModel="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                placeholder="tu@email.com"
            />

            <x-forms.input
                name="password"
                wireModel="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Ingresa tu contraseña"
                viewable
            />

            <x-forms.input
                name="password_confirmation"
                wireModel="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                placeholder="Confirme contraseña"
                viewable
            />

            <x-button variant="primary" type="submit" class="w-full">
                {{ __('Crear cuenta') }}
            </x-button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">
                {{ __('¿Ya tiene una cuenta?') }}
                <a href="{{ route('login') }}" class="text-moto-red hover:text-red-700 font-medium" wire:navigate>
                    {{ __('Inicie sesión') }}
                </a>
            </p>
        </div>
    </div>
</div>
