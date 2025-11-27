<div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-md w-full mx-auto">
    <div class="mb-6 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
            <i class="fas fa-shield-alt text-yellow-600 text-xl"></i>
        </div>
        <h2 class="text-xl font-bold text-moto-black">{{ __('Confirmación de Seguridad') }}</h2>
        <p class="text-sm text-gray-600 mt-2">
            {{ __('Esta es un área segura. Por favor, confirma tu contraseña para continuar.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form wire:submit="confirmPassword" class="space-y-6">
        <x-forms.input
            name="password"
            wireModel="password"
            :label="__('Contraseña Actual')"
            type="password"
            required
            autocomplete="current-password"
            placeholder="Tu contraseña"
            viewable
            icon="fas fa-key"
        />

        <x-button variant="primary" type="submit" class="w-full">
            {{ __('Confirmar') }}
        </x-button>
    </form>
</div>
