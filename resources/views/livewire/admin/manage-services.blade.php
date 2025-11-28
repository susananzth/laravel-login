<div class="space-y-6">
    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-moto-black">Catálogo de Servicios</h2>
            <p class="text-gray-600 text-sm">Gestiona los servicios ofrecidos en el taller.</p>
        </div>
        @can('services.create')
        <x-button wire:click="create" icon="fas fa-plus">
            Nuevo Servicio
        </x-button>
        @endcan
    </div>

    {{-- Tabla de Servicios --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Duración</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($services as $service)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $service->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">
                                S/. {{ number_format($service->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $service->duration_minutes }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = $service->is_active ? 'green' : 'yellow';
                                    $badgeName = $service->is_active ? 'Activo' : 'Inactivo';
                                @endphp
                                <x-badge :color="$badgeColor" :label="$badgeName" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @canany(['services.view', 'services.edit'])
                                    <button wire:click="edit({{ $service->id }})" class="text-gray-400 hover:text-moto-red transition duration-200 mr-3">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                @endcanany
                                @can('services.delete')
                                    <button wire:click="delete({{ $service->id }})"
                                        onclick="confirm('¿Estás seguro(a) de eliminar este servicio? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()"
                                        class="text-gray-400 hover:text-red-600 transition duration-200">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No hay servicios registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $services->links() }}
        </div>
    </div>

    @canany(['services.view', 'services.edit'])
    {{-- MODAL DE SERVICIOS --}}
    <x-modal name="service-manager-modal" :show="$showModal" maxWidth="md">
        <form wire:submit.prevent="save" id="serviceForm">

            <div class="pb-6 border-b border-gray-100 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-moto-black flex items-center">
                    <i class="fas {{ $serviceId ? 'fa-pen-to-square' : 'fa-plus' }} text-moto-red mr-2"></i>
                    {{ $serviceId ? 'Editar Servicio' : 'Nuevo Servicio' }}
                </h3>
                <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-moto-red transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-5">
                <x-forms.input
                    label="Nombre del Servicio"
                    name="name"
                    wireModel="name"
                    icon="fas fa-tag"
                    placeholder="Ej: Cambio de Aceite"
                    maxlength="70"
                    required
                />

                <div class="grid grid-cols-2 gap-4">
                    <x-forms.input
                        label="Precio (S/.)"
                        name="price"
                        type="number"
                        step="0.01"
                        wireModel="price"
                        icon="fas fa-money-bill"
                        placeholder="0.00"
                        maxlength="10"
                        required
                    />
                    <x-forms.input
                        label="Duración (min)"
                        name="duration_minutes"
                        type="number"
                        wireModel="duration_minutes"
                        icon="fas fa-clock"
                        placeholder="60"
                        required
                    />
                </div>

                <x-forms.input
                    label="Descripción"
                    name="description"
                    wireModel="description"
                    type="textarea"
                    maxlength="255"
                    placeholder="Detalles del servicio..."
                />

                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-moto-red shadow-sm focus:ring-moto-red mr-2">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Servicio Activo (Visible para clientes)</label>
                </div>
            </div>

            <x-slot name="footer">
                <x-button type="button" variant="secondary" wire:click="$set('showModal', false)">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="ml-3" form="serviceForm">
                    {{ $serviceId ? 'Actualizar' : 'Guardar Servicio' }}
                </x-button>
            </x-slot>
        </form>
    </x-modal>
    @endcanany
</div>

@script
<script>
    Livewire.on('show-service-modal-changed', ([value]) => {
        if (value) {
            $dispatch('open-modal', 'service-manager-modal');
        } else {
            $dispatch('close-modal');
        }
    });
</script>
@endscript
