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
            wireModel="date"
            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
        />

        @if($service_id && $date)
            <div>
                <label class="block text-sm font-semibold text-moto-black mb-2">Hora Disponible</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($this->availableSlots as $slot)
                        <button
                            type="button"
                            class="px-3 py-2 text-sm rounded border {{ $time == $slot ? 'bg-moto-red text-white border-moto-red' : 'bg-white text-gray-700 hover:border-moto-red' }}"
                            wire:click="$set('time', '{{ $slot }}')"
                        >
                            {{ $slot }}
                        </button>
                    @endforeach
                </div>
                @error('time') <span class="text-red-500 text-sm">Seleccione una hora</span> @enderror
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
