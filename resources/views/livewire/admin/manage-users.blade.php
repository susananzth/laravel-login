<div class="space-y-6">
    {{-- Encabezado de la Sección --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-moto-black">Gestión de Usuarios</h2>
            <p class="text-gray-600 text-sm">Administra el acceso y roles del personal y clientes.</p>
        </div>
        @can('users.create')
        <x-button wire:click="create" icon="fas fa-plus">
            Nuevo Usuario
        </x-button>
        @endcan
    </div>

    {{-- Tabla de Usuarios --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            {{-- Columna Nombre/Email --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-moto-black text-white flex items-center justify-center font-bold text-sm">
                                        {{ substr($user->firstname, 0, 1) }}
                                        {{ substr($user->lastname, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->firstname . ' ' . $user->lastname }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Columna Roles (Usando x-badge o span directo) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($user->roles as $role)
                                    @php
                                        $badgeColor = match($role->name) {
                                            'Administrador' => 'purple',
                                            'Ténico' => 'blue',
                                            'Cliente' => 'green',
                                            default => 'gray'
                                        };
                                    @endphp
                                    <x-badge :color="$badgeColor" :label="__( $role->name )" />
                                @endforeach
                            </td>

                            {{-- Columna Fecha --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>

                            {{-- Columna Acciones --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @canany(['users.view', 'users.edit'])
                                    <button wire:click="edit({{ $user->id }})" class="text-gray-400 hover:text-moto-red transition duration-200 mr-3" title="Editar">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                @endcanany
                                @can('users.delete')
                                    <button wire:click="delete({{ $user->id }})"
                                            onclick="confirm('¿Estás seguro(a) de eliminar este usuario? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()"
                                            class="text-gray-400 hover:text-red-600 transition duration-200" title="Eliminar">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No hay usuarios registrados aún.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>

    @canany(['users.view', 'users.edit'])
    {{-- MODAL DE CREACIÓN / EDICIÓN --}}
    <x-modal name="user-manager-modal" :show="$showModal" maxWidth="md">
        <form wire:submit.prevent="save" id="userForm">
            {{-- Cabecera del Modal (Consistente con el calendario) --}}
            <div class="pb-6 border-b border-gray-100 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-moto-black flex items-center">
                    <i class="fas {{ $userId ? 'fa-user-edit' : 'fa-user-plus' }} text-moto-red mr-2"></i>
                    {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
                </h3>
                <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-moto-red transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-forms.input
                        label="Nombres"
                        name="firstname"
                        wireModel="firstname"
                        icon="fas fa-user"
                        placeholder="Ej: Juan"
                        required
                    />
                    <x-forms.input
                        label="Apellidos"
                        name="lastname"
                        wireModel="lastname"
                        placeholder="Ej: Pérez"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-forms.input
                        label="Usuario"
                        name="username"
                        wireModel="username"
                        icon="fas fa-at"
                        placeholder="juanperez"
                        required
                    />
                    <x-forms.input
                        label="Teléfono"
                        name="phone"
                        wireModel="phone"
                        icon="fas fa-phone"
                        placeholder="999 999 999"
                        required
                    />
                </div>

                <x-forms.input
                    label="Correo Electrónico"
                    name="email"
                    type="email"
                    wireModel="email"
                    icon="fas fa-envelope"
                    placeholder="ejemplo@correo.com"
                    required
                />

                <div>
                    <label class="block text-sm font-semibold text-moto-black mb-2">Roles Asignados</label>

                    {{-- Grid de Checkboxes --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 border border-gray-200 rounded-lg bg-gray-50">
                        @foreach($availableRoles as $role)
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <input
                                    type="checkbox"
                                    wire:model="roles"
                                    value="{{ $role->name }}"
                                    class="form-checkbox h-5 w-5 text-moto-red rounded border-gray-300 focus:ring-moto-red transition duration-150 ease-in-out"
                                >
                                <span class="text-sm font-medium text-gray-700 capitalize">
                                    {{ $role->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('roles')
                        <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Solo mostramos password si es creación o si el admin quiere cambiarla --}}
                <div x-data="{ changePass: {{ $userId ? 'false' : 'true' }} }">
                    @if($userId)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" x-model="changePass" id="change_pass" class="rounded border-gray-300 text-moto-red shadow-sm focus:ring-moto-red mr-2">
                            <label for="change_pass" class="text-sm text-gray-600 cursor-pointer">Cambiar contraseña</label>
                        </div>
                    @endif

                    <div x-show="changePass" x-transition>
                        <x-forms.input
                            label="Contraseña"
                            name="password"
                            type="password"
                            wireModel="password"
                            icon="fas fa-lock"
                            viewable
                            :required="!$userId"
                        />
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <x-button type="button" variant="secondary" wire:click="$set('showModal', false)">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="ml-3" form="userForm">
                    {{ $userId ? 'Actualizar' : 'Guardar Usuario' }}
                </x-button>
            </x-slot>

        </form>
    </x-modal>
    @endcanany
</div>

{{-- Script para sincronizar el modal con Alpine --}}
@script
<script>
    Livewire.on('show-modal-changed', ([value]) => {
        if (value) {
            $dispatch('open-modal', 'user-manager-modal');
        } else {
            $dispatch('close-modal');
        }
    });
</script>
@endscript
