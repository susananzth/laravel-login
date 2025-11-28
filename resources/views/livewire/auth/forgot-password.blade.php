<div class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-md w-full mx-auto">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-moto-black mb-2">{{ __('Recuperar Contraseña') }}</h2>
            <p class="text-sm text-gray-600">
                {{ __('Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.') }}
            </p>
        </div>

        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-6">
            <x-forms.input
                name="email"
                wireModel="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autofocus
                placeholder="tu@email.com"
                icon="fas fa-envelope"
            />

            <x-button variant="primary" type="submit" class="w-full">
                {{ __('Enviar enlace de recuperación') }}
            </x-button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                {{ __('¿Lo recordaste?') }}
                <a href="{{ route('login') }}" class="text-moto-red hover:text-red-700 font-medium transition duration-150">
                    {{ __('Volver al inicio de sesión') }}
                </a>
            </p>
        </div>
    </div>
</div>
