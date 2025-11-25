<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-moto-black">Roles y Permisos</h2>
            <p class="text-gray-600 text-sm">Define qué pueden hacer los usuarios en el sistema.</p>
        </div>
        <x-button wire:click="create" icon="fas fa-shield-alt">Nuevo Rol</x-button>
    </div>

    {{-- Tabla de Roles --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Rol</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Permisos Asignados</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($roles as $role)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-moto-black">
                            {{ ucfirst($role->name) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($role->permissions as $perm)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $perm->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 text-xs italic">Sin permisos</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button wire:click="edit({{ $role->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            @unless(in_array($role->name, ['admin', 'client', 'technician']))
                                <button wire:click="delete({{ $role->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL DE ROLES --}}
    <x-modal name="role-manager-modal" :show="$showModal" maxWidth="2xl">
        <form wire:submit.prevent="save">
            <div class="bg-gray-50 pb-4 border-b border-gray-100 px-6 pt-6 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-moto-black">
                    {{ $roleId ? 'Editar Rol' : 'Crear Nuevo Rol' }}
                </h3>
                <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-moto-red">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <x-forms.input label="Nombre del Rol" wireModel="name" placeholder="Ej: supervisor" required />

                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider border-b pb-1">Permisos</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($permissions as $group => $perms)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h5 class="font-bold text-moto-red capitalize mb-3">{{ $group }}</h5>
                                <div class="space-y-2">
                                    @foreach($perms as $perm)
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                wire:model="selectedPermissions"
                                                value="{{ $perm->name }}"
                                                id="perm_{{ $perm->id }}"
                                                class="rounded border-gray-300 text-moto-red shadow-sm focus:border-moto-red focus:ring focus:ring-moto-red focus:ring-opacity-50"
                                            >
                                            <label for="perm_{{ $perm->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                {{ str_replace($group.'.', '', $perm->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse rounded-b-lg">
                <x-button type="submit">Guardar Rol</x-button>
                <x-button type="button" variant="secondary" class="mr-3" wire:click="$set('showModal', false)">Cancelar</x-button>
            </div>
        </form>
    </x-modal>
</div>

@script
<script>
    Livewire.on('show-role-modal-changed', ([value]) => {
        if (value) { $dispatch('open-modal', 'role-manager-modal'); }
        else { $dispatch('close-modal'); }
    });
</script>
@endscript
