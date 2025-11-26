<x-settings.layout :heading="__('Perfil de Usuario')" :subheading="__('Actualiza tu información personal.')">
    <form wire:submit="updateProfileInformation" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-forms.input
                label="Nombres"
                name="firstname"
                wireModel="firstname"
                required
            />

            <x-forms.input
                label="Apellidos"
                name="lastname"
                wireModel="lastname"
                required
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-forms.input
                label="Usuario"
                name="username"
                wireModel="username"
                required
            />

            <x-forms.input
                label="Teléfono"
                name="phone"
                wireModel="phone"
                required
            />
        </div>

        <x-forms.input
            label="Correo Electrónico"
            name="email"
            type="email"
            wireModel="email"
            required
        />

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                <p class="text-sm text-yellow-800">
                    {{ __('Tu correo no ha sido verificado.') }}
                    <button wire:click.prevent="resendVerificationNotification" class="underline text-yellow-900 hover:text-yellow-700 font-bold">
                        {{ __('Reenviar correo de verificación.') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-green-600 text-sm">
                        {{ __('Un nuevo enlace de verificación ha sido enviado.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex justify-end pt-4">
            <x-button type="submit">
                {{ __('Guardar Cambios') }}
            </x-button>
        </div>
    </form>

    <div class="border-t my-8"></div>

    <livewire:settings.delete-user-form />
</x-settings.layout>
