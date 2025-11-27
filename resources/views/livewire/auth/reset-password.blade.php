<div class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-md w-full mx-auto">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-moto-black">{{ __('Nueva Contraseña') }}</h2>
            <p class="text-sm text-gray-600 mt-2">{{ __('Ingresa y confirma tu nueva contraseña.') }}</p>
        </div>

        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form wire:submit="resetPassword" class="space-y-6">
            <x-forms.input
                name="email"
                wireModel="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                readonly
                class="bg-gray-50 cursor-not-allowed" {{-- Visualmente bloqueado --}}
                icon="fas fa-envelope"
            />

            <x-forms.input
                name="password"
                wireModel="password"
                :label="__('Nueva Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
                viewable
                icon="fas fa-lock"
            />

            <x-forms.input
                name="password_confirmation"
                wireModel="password_confirmation"
                :label="__('Confirmar Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Repite la contraseña"
                viewable
                icon="fas fa-lock"
            />

            <x-button variant="primary" type="submit" class="w-full">
                {{ __('Restablecer Contraseña') }}
            </x-button>
        </form>
    </div>
</div>
