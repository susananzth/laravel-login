<div>
    {{-- Leyenda de Estados --}}
    <div class="mb-6 flex flex-wrap justify-end items-center border-b pb-4">
        <div class="flex flex-wrap md:flex-nowrap gap-3 text-sm text-gray-700 mt-2 sm:mt-0">
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-yellow-500 mr-1 shadow"></span> Pendiente</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-1 shadow"></span> Confirmada</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-1 shadow"></span> Completada</span>
            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-1 shadow"></span> Cancelada</span>
        </div>
    </div>

    {{-- Contenedor del Calendario FullCalendar --}}
    <div wire:ignore
        x-data="{
            calendar: null,
            init() {
                let calendarEl = this.$refs.calendar;
                this.calendar = new window.Calendar(calendarEl, {
                    plugins: [ window.dayGridPlugin, window.timeGridPlugin, window.listPlugin, window.interactionPlugin ],
                    initialView: 'dayGridMonth',
                    locale: window.esLocale,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: (info, successCallback, failureCallback) => {
                        // Llamamos al método PHP para obtener eventos
                        @this.getEvents().then(events => {
                            successCallback(events);
                        });
                    },
                    editable: {{ Auth::user()->hasAnyPermission(['appointments.edit', 'appointments.be_assigned', 'appointments.assign', 'appointments.complete']) ? 'false' : 'true' }},
                    eventDrop: (info) => {
                        if (!confirm('Estás cambiando la fecha de la cita. ¿Confirmar cambio?')) {
                            info.revert(); // Si dice que NO, devolvemos la cita a su lugar
                            return;
                        }
                        // Si dice que SÍ, actualizamos en backend
                        @this.updateAppointmentDate(info.event.id, info.event.startStr);
                    },
                    eventClick: (info) => {
                        // Llamamos al método Livewire para cargar la cita
                        @this.editAppointment(info.event.id);
                    },
                });
                this.calendar.render();

                // Escuchar evento de Livewire para recargar
                Livewire.on('refresh-calendar', () => {
                    this.calendar.refetchEvents();
                });
            }
        }"
    >
        <div x-ref="calendar"></div>
    </div>

    {{-- MODAL DE GESTIÓN DE CITA --}}
    @canany(['appointments.be_assigned', 'appointments.assign', 'appointments.complete'])
        {{-- Usamos x-modal y Livewire para controlar la visibilidad con 'showModal' --}}
        <x-modal name="admin-appointment-manager" :show="$showModal" maxWidth="lg">

            <form wire:submit.prevent="updateAppointment" id="appointmentForm">
                <div class="pb-6 border-b border-gray-100 flex justify-between items-center rounded-t-lg">
                    <h3 class="text-lg font-bold text-moto-black flex items-center">
                        <i class="fas fa-motorcycle text-moto-red mr-2"></i>
                        Gestionar Cita #{{ $selectedAppointment?->id }}
                    </h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-moto-red transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Resumen de la Cita --}}
                <div class="grid grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded-lg border border-blue-100 mb-6">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase tracking-wider font-semibold">Cliente</span>
                        <span class="text-moto-black font-medium">
                            {{ $selectedAppointment?->client->firstname . ' ' . $selectedAppointment?->client->lastname }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase tracking-wider font-semibold">Servicio</span>
                        <span class="text-moto-black font-medium">{{ $selectedAppointment?->service->name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase tracking-wider font-semibold">Fecha</span>
                        <span class="text-moto-black font-medium">{{ $selectedAppointment?->scheduled_at?->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase tracking-wider font-semibold">Hora</span>
                        <span class="text-moto-black font-medium">{{ $selectedAppointment?->scheduled_at?->format('H:i') }} h</span>
                    </div>
                </div>

                <div class="space-y-5">
                    <x-forms.select
                        label="Asignar Técnico"
                        name="technician_id"
                        wireModel="technician_id"
                        icon="fas fa-tools"
                        placeholder="-- Seleccionar Técnico --"
                    >
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->firstname . ' ' . $tech->lastname }}</option>
                        @endforeach
                    </x-forms.select>

                    <x-forms.select
                        label="Estado de la Cita"
                        name="status"
                        wireModel="status"
                        icon="fas fa-list-check"
                        required
                    >
                        <option value="pending">Pendiente</option>
                        <option value="confirmed">Confirmada</option>
                        <option value="in_progress">En Proceso</option>
                        <option value="completed">Completada</option>
                        <option value="cancelled">Cancelada</option>
                    </x-forms.select>

                    <x-forms.input
                        label="Notas Internas"
                        name="adminNotes"
                        wireModel="adminNotes"
                        placeholder="Escribe notas para el técnico..."
                        type="textarea"
                    />
                </div>

                <x-slot name="footer">
                    {{-- Botón de Cerrar usando el estilo 'secondary' del componente x-button --}}
                    <x-button type="button" variant="secondary" wire:click="$set('showModal', false)">
                        Cancelar
                    </x-button>
                    {{-- Botón de Guardar usando el estilo 'primary' del componente x-button --}}
                    <x-button type="submit" variant="primary" class="ml-3" form="appointmentForm">
                        Guardar Cambios
                    </x-button>
                </x-slot>

            </form>
        </x-modal>
    @endcanany

</div>

{{-- Script para abrir el modal desde Livewire/Alpine --}}
@script
<script>
    // Este watcher garantiza que el modal se muestre/oculte correctamente cuando Livewire actualiza $showModal
    // Usamos el evento de ventana que el componente x-modal está escuchando.
    Livewire.on('show-modal-changed', ([value]) => {
        if (value) {
            $dispatch('open-modal', 'admin-appointment-manager');
        } else {
            $dispatch('close-modal');
        }
    });

    // Pequeño hack para que Livewire sepa que el modal se cerró desde el backdrop o Escape
    Livewire.hook('element.removed', (el, component) => {
        if (el.tagName.toLowerCase() === 'body' && component.name.includes('appointment-calendar')) {
            // Asumiendo que el modal se elimina al cerrar (depende de cómo lo cierres),
            // pero es mejor confiar en el evento de Livewire para la gestión.
        }
    });
</script>
@endscript
