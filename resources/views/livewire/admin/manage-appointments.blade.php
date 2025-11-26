<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-moto-black">Gestión de Citas</h2>
            <p class="text-gray-600 text-sm">Vista tabular para control administrativo.</p>
        </div>
        {{-- Botón opcional si quisieras crear citas desde admin (no requerido ahora) --}}
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">ID / Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Cliente / Servicio</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Técnico</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $cita)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-moto-black">#{{ $cita->id }}</div>
                                <div class="text-sm text-gray-500">{{ $cita->scheduled_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $cita->client->firstname . ' ' . $cita->client->lastname }}</div>
                                <div class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded">{{ $cita->service->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($cita->technician)
                                    <span class="flex items-center text-blue-600 font-medium">
                                        <i class="fas fa-wrench text-xs mr-1"></i> {{ $cita->technician->firstname . ' ' . $cita->technician->lastname }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-- Sin asignar --</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $color = match($cita->status) {
                                        'pending' => 'yellow',
                                        'confirmed' => 'blue',
                                        'in_progress' => 'purple',
                                        'completed' => 'green',
                                        'cancelled' => 'red',
                                        default => 'gray'
                                    };
                                    $label = match($cita->status) {
                                        'pending' => 'Pendiente',
                                        'confirmed' => 'Confirmada',
                                        'in_progress' => 'En Taller',
                                        'completed' => 'Finalizada',
                                        'cancelled' => 'Cancelada',
                                        default => $cita->status
                                    };
                                @endphp
                                <x-badge :color="$color" :label="$label" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $cita->id }})" class="text-gray-400 hover:text-moto-red transition duration-200 mr-3" title="Editar">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button wire:click="delete({{ $cita->id }})"
                                    onclick="confirm('¿Estás seguro(a) Cancelar esta cita?') || event.stopImmediatePropagation()"
                                    class="text-gray-400 hover:text-red-600 transition duration-200" title="Eliminar">
                                    <i class="fas fa-ban text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No hay citas registrados aún.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $appointments->links() }}
        </div>
    </div>

    {{-- MODAL DE EDICIÓN --}}
    <x-modal name="appointment-manager-modal" :show="$showModal" maxWidth="lg">
        <form wire:submit.prevent="save" id="appointmentForm">
            <div class="pb-6 border-b border-gray-100 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-moto-black flex items-center">
                    <i class="fas fa-pen-to-square text-moto-red mr-2"></i>
                    Editar Cita #{{ $appointmentId }}
                </h3>
                <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-moto-red transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-5">
                {{-- Info solo lectura --}}
                <div class="grid grid-cols-2 gap-4 bg-blue-50 p-3 rounded-lg text-sm mb-4">
                    <div>
                        <span class="block text-gray-500 text-xs uppercase">Cliente</span>
                        <span class="font-bold text-gray-800">{{ $client_name }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs uppercase">Servicio</span>
                        <span class="font-bold text-gray-800">{{ $service_name }}</span>
                    </div>
                </div>

                {{-- Edición de Fecha y Hora --}}
                <div class="grid grid-cols-2 gap-4">
                    <x-forms.input type="date" label="Fecha" name="date" wireModel="date" required />
                    <x-forms.input type="time" label="Hora" name="time" wireModel="time" required />
                </div>

                <x-forms.select label="Técnico" name="technician_id" wireModel="technician_id" icon="fas fa-user-cog">
                    <option value="">-- Sin Asignar --</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}">
                            {{ $tech->firstname . ' ' . $tech->lastname }}
                        </option>
                    @endforeach
                </x-forms.select>

                <x-forms.select label="Estado" name="status" wireModel="status" required icon="fas fa-info-circle">
                    <option value="pending">Pendiente</option>
                    <option value="confirmed">Confirmada</option>
                    <option value="in_progress">En Proceso</option>
                    <option value="completed">Completada</option>
                    <option value="cancelled">Cancelada</option>
                </x-forms.select>

                <x-forms.input label="Notas" name="notes" wireModel="notes" />
            </div>

            <x-slot name="footer">
                <x-button type="button" variant="secondary" wire:click="$set('showModal', false)">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="ml-3" form="appointmentForm">
                    Actualizar
                </x-button>
            </x-slot>
        </form>
    </x-modal>
</div>

@script
<script>
    Livewire.on('show-appointment-modal-changed', ([value]) => {
        if (value) {
            $dispatch('open-modal', 'appointment-manager-modal');
        } else {
            $dispatch('close-modal');
        }
    });
</script>
@endscript
