<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-moto-black mb-6">Agendar Servicio</h2>

    <form wire:submit="save" class="space-y-6">

        <div>
            <label class="block text-sm font-semibold text-moto-black mb-2">Servicio</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($this->services as $service)
                    <div
                        class="border rounded-lg p-4 cursor-pointer transition {{ $service_id == $service->id ? 'border-moto-red bg-red-50 ring-1 ring-moto-red' : 'border-gray-200 hover:border-moto-red' }}"
                        wire:click="$set('service_id', {{ $service->id }})"
                    >
                        <div class="font-bold text-moto-black">{{ $service->name }}</div>
                        <div class="text-sm text-gray-500">S/. {{ $service->price }}</div>
                    </div>
                @endforeach
            </div>
            @error('service_id') <span class="text-red-500 text-sm">Seleccione un servicio</span> @enderror
        </div>

        <x-forms.input
            type="date"
            label="Fecha"
            name="date"
            wireModel="date.live"
            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
        />

        @if($service_id && $date)
            <div class="mb-6">
                <label class="block text-sm font-semibold text-moto-black mb-2">
                    Hora Disponible
                    {{-- Spinner de carga --}}
                    <span wire:loading wire:target="date" class="text-xs text-moto-red font-normal ml-2">
                        <i class="fas fa-spinner fa-spin"></i> Calculando...
                    </span>
                </label>

                {{-- Debug temporal: Si esto imprime [], es que la lógica backend retorna vacío --}}
                {{-- @dump($this->availableSlots) --}}

                @if(count($this->availableSlots) > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                        @foreach($this->availableSlots as $slot)
                            <button
                                type="button"
                                wire:click="$set('time', '{{ $slot }}')"
                                class="px-2 py-2 text-sm rounded border transition duration-150
                                {{ $time === $slot 
                                    ? 'bg-moto-red text-white border-moto-red shadow-md transform scale-105' 
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-moto-red hover:text-moto-red' 
                                }}"
                            >
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>
                @else
                    {{-- Feedback visual si no hay horas --}}
                    <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-lg text-yellow-700 text-sm flex items-center">
                        <i class="fas fa-calendar-times mr-2 text-lg"></i>
                        <span>
                            No hay horarios disponibles para esta fecha. 
                            <span class="block text-xs mt-1 text-yellow-600">
                                (Puede ser fin de semana, fecha pasada o agenda llena).
                            </span>
                        </span>
                    </div>
                @endif
                
                @error('time') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        @endif

        <x-forms.input
            label="Notas adicionales (Opcional)"
            name="notes"
            wireModel="notes"
            placeholder="Escribe notas..."
            type="textarea"
            maxlength="500"
            oninput="this.value = this.value.slice(0, 500)"
        />

        <div class="pt-4">
            <x-button type="submit" class="w-full" loading>
                Confirmar Cita
            </x-button>
        </div>
    </form>
</div>
