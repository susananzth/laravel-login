<section class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">{{ __('Eliminar Cuenta') }}</h3>
            <p class="mt-1 text-sm text-gray-600">{{ __('Una vez eliminada, todos los recursos y datos se perderán permanentemente.') }}</p>
        </div>

        <x-button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Eliminar Cuenta') }}
        </x-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable maxWidth="md">
        <form wire:submit="deleteUser" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Por favor ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}
            </p>

            <div class="mt-6">
                <x-forms.input
                    label="Contraseña"
                    type="password"
                    name="password"
                    wireModel="password"
                    placeholder="Contraseña"
                    viewable
                />
            </div>

            <div class="mt-6 flex justify-end">
                <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal')">
                    {{ __('Cancelar') }}
                </x-button>

                <x-button type="submit" variant="danger" class="ml-3">
                    {{ __('Eliminar Cuenta') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</section>
