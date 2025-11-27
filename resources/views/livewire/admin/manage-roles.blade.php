<div class="space-y-6">
    {{-- Encabezado de la Sección --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-moto-black">Roles y Permisos</h2>
            <p class="text-gray-600 text-sm">Define qué pueden hacer los usuarios en el sistema.</p>
        </div>
        @can('roles.create')
        <x-button wire:click="create" icon="fas fa-plus">
            Nuevo Rol
        </x-button>
        @endcan
    </div>

    {{-- Tabla de Roles --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Rol</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Permisos Asignados</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-moto-black">
                                {{ ucfirst($role->name) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    {{ count($role->permissions) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @canany(['roles.view', 'roles.edit'])
                                    <button wire:click="edit({{ $role->id }})" class="text-gray-400 hover:text-moto-red transition duration-200 mr-3" title="Editar">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                @endcanany
                                @can('roles.delete')
                                    @unless(in_array($role->id, [1, 2, 3]))
                                        <button wire:click="delete({{ $role->id }})"
                                            onclick="confirm('¿Estás seguro(a) de eliminar este rol? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()"
                                                class="text-gray-400 hover:text-red-600 transition duration-200" title="Eliminar">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    @endunless
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No hay roles registrados aún.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @canany(['roles.view', 'roles.edit'])
    {{-- MODAL DE ROLES --}}
    <x-modal name="role-manager-modal" :show="$showModal" maxWidth="2xl">
        <form wire:submit.prevent="save" id="roleForm">
            <div class="pb-6 border-b border-gray-100 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-moto-black flex items-center">
                    <i class="fas {{ $roleId ? 'fa-pen-to-square' : 'fa-plus' }} text-moto-red mr-2"></i>
                    {{ $roleId ? 'Editar Rol' : 'Crear Nuevo Rol' }}
                </h3>
                <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-moto-red transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-5 max-h-[70vh] overflow-y-auto">
                <x-forms.input
                    label="Nombre del Rol"
                    name="name"
                    wireModel="name"
                    placeholder="Ej: supervisor"
                    required
                    {{-- Si es rol de sistema, lo deshabilitamos visualmente --}}
                    :disabled="$isSystemRole && $roleId"
                    class="{{ $isSystemRole && $roleId ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                />
                @if($isSystemRole && $roleId)
                    <p class="text-xs text-yellow-600 mt-1">
                        <i class="fas fa-lock mr-1"></i> Este es un rol del sistema, no se puede editar.
                    </p>
                @endif

                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider border-b pb-1">Permisos</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($permissions as $group => $perms)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h5 class="font-bold text-moto-red capitalize mb-3">{{ $group }}</h5>
                                <div class="space-y-2">
                                    @foreach($perms as $perm)
                                        <div class="flex items-start space-x-3" wire:key="perm-{{ $perm->id }}">
                                            <div class="flex items-center h-5 mt-0.5">
                                                <input
                                                    id="perm_{{ $perm->id }}"
                                                    type="checkbox"
                                                    wire:model="selectedPermissions"
                                                    value="{{ $perm->name }}"
                                                    {{ ($isSystemRole && $roleId) ? 'disabled' : '' }}
                                                    class="{{ $isSystemRole && $roleId ? 'bg-gray-100 cursor-not-allowed' : 'bg-white' }} w-4 h-4 text-moto-red border-gray-300 rounded focus:ring-moto-red focus:ring-2 transition duration-200"
                                                >
                                            </div>
                                            <label for="perm_{{ $perm->id }}" class="text-sm font-medium text-moto-black cursor-pointer select-none hover:text-gray-700 transition duration-200">
                                                {{ __(ucfirst(str_replace(['.', '_'], ' ', explode('.', $perm->name)[1] ?? $perm->name))) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <x-button type="button" variant="secondary" wire:click="$set('showModal', false)">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="ml-3" form="roleForm">
                    {{ $roleId ? 'Actualizar' : 'Guardar Rol' }}
                </x-button>
            </x-slot>
        </form>
    </x-modal>
    @endcanany
</div>

@script
<script>
    Livewire.on('show-role-modal-changed', ([value]) => {
        if (value) {
            $dispatch('open-modal', 'role-manager-modal');
        } else {
            $dispatch('close-modal');
        }
    });
</script>
@endscript
