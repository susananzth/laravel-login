<div class="space-y-8">

    {{-- Header y Filtros --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-moto-black">
                <i class="fas fa-chart-area text-moto-red me-1"></i>
                Reportes y Estadísticas
            </h2>
            <p class="text-gray-500 text-sm">Analiza el rendimiento de tu taller.</p>
        </div>

        <div class="flex gap-2">
            <select wire:model.live="range" class="rounded-lg border-gray-300 text-sm focus:ring-moto-red focus:border-moto-red">
                <option value="month">Este Mes</option>
                <option value="last_3_months">Últimos 3 Meses</option>
                <option value="year">Este Año</option>
                <option value="custom">Personalizado</option>
            </select>

            @if($range === 'custom')
                <input type="date" wire:model.live="startDate" class="rounded-lg border-gray-300 text-sm">
                <input type="date" wire:model.live="endDate" class="rounded-lg border-gray-300 text-sm">
            @endif
        </div>
    </div>

    {{-- Grilla de Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Gráfico 1: Ingresos --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-4">Ingresos (Citas Completadas)</h3>

            <div
                wire:key="income-chart-{{ $range }}"
                class="relative h-64"
                x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'line',
                            data: {
                                labels: @js($incomeLabels),
                                datasets: [{
                                    label: 'Ingresos (S/.)',
                                    data: @js($incomeValues),
                                    borderColor: '#DC2626',
                                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    }
                }"
            >
                {{-- Usamos x-ref en lugar de id para evitar conflictos --}}
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        {{-- Gráfico 2: Servicios Populares --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-4">Servicios Más Solicitados</h3>

            <div
                wire:key="services-chart-{{ $range }}"
                class="relative h-64 flex justify-center"
                x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: @js($serviceLabels),
                                datasets: [{
                                    data: @js($serviceValues),
                                    backgroundColor: ['#1A1A1A', '#DC2626', '#F59E0B', '#10B981', '#3B82F6'],
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    }
                }"
            >
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        {{-- Gráfico 3: Estados --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 lg:col-span-2">
            <h3 class="font-bold text-gray-700 mb-4">Resumen de Estados</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                @foreach($statusData as $stat)
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs uppercase text-gray-500 font-bold">{{ __($stat->status) }}</div>
                        <div class="text-xl font-black text-moto-black">{{ $stat->total }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
