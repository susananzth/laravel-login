<x-layouts.app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @role('admin')
                <h2 class="text-3xl font-extrabold text-moto-black border-b pb-2 mb-6">
                    Panel de Administración ⚙️
                </h2>

                {{-- Tarjetas de Resumen (Dashboard Cards) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Tarjeta 1: Citas Hoy --}}
                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border-l-4 border-moto-red p-6 transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Citas para Hoy</div>
                                <div class="text-4xl font-bold text-moto-black mt-1">
                                    {{ $stats['today'] }}
                                </div>
                            </div>
                            <i class="fas fa-calendar-alt text-4xl text-moto-red opacity-70"></i>
                        </div>
                    </div>

                    {{-- Tarjeta 2: Citas Pendientes --}}
                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border-l-4 border-yellow-500 p-6 transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Citas Pendientes</div>
                                <div class="text-4xl font-bold text-moto-black mt-1">
                                    {{ \App\Models\Appointment::where('status', 'pending')->count() }}
                                </div>
                            </div>
                            <i class="fas fa-clock text-4xl text-yellow-500 opacity-70"></i>
                        </div>
                    </div>
                </div>
            @endrole

            {{-- Sección de Calendario --}}
            <div class="p-6 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <h3 class="text-2xl font-bold text-moto-black my-3">Calendario de Citas 🗓️</h3>
                <livewire:appointments.appointment-calendar />
            </div>

            @role('client')
                {{-- Sección de Acción para Clientes --}}
                <div class="bg-moto-red/10 overflow-hidden shadow-lg sm:rounded-2xl p-8 mt-8 border border-moto-red/30">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div>
                            <h3 class="text-xl font-bold text-moto-black">¿Tu moto necesita servicio?</h3>
                            <p class="text-gray-700 mt-1">Reserva tu próxima cita con nuestros expertos de forma rápida y sencilla.</p>
                        </div>
                        <x-button variant="primary" onclick="window.location='{{ route('appointments.create') }}'" icon="fas fa-plus-circle">
                            Agendar Nueva Cita
                        </x-button>
                    </div>
                </div>
            @endrole

        </div>
    </div>
</x-layouts.app>
