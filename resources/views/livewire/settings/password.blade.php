<x-settings.layout :heading="__('Actualizar Contraseña')" :subheading="__('Asegura tu cuenta usando una contraseña larga y segura.')">
    <form wire:submit="updatePassword" class="space-y-6 max-w-xl">

        <x-forms.input
            label="Contraseña Actual"
            name="current_password"
            type="password"
            wireModel="current_password"
            required
            viewable
        />

        <x-forms.input
            label="Nueva Contraseña"
            name="password"
            type="password"
            wireModel="password"
            required
            viewable
        />

        <x-forms.input
            label="Confirmar Contraseña"
            name="password_confirmation"
            type="password"
            wireModel="password_confirmation"
            required
            viewable
        />

        <div class="flex justify-end pt-4">
            <x-button type="submit">
                {{ __('Guardar Contraseña') }}
            </x-button>
        </div>
    </form>
</x-settings.layout>
