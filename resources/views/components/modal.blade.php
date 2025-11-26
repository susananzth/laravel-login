@props([
    'name', // Nombre único para el modal (ej: 'appointment-details')
    'maxWidth' => '2xl', // Tallas predefinidas: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
    'show' => false, // Para mostrar/ocultar con Livewire si es necesario
    'closeable' => true, // Si se puede cerrar haciendo clic en el backdrop o con Esc
])

@php
    $maxWidthClasses = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
    ][$maxWidth];
@endphp

<div
    x-data="{ show: @js($show) }"
    x-show="show"
    x-on:close-modal.window="show = false"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:keydown.escape.window="{{ $closeable ? 'show = false' : '' }}"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: {{ $show ? 'block' : 'none' }}"
>
    {{-- Backdrop (Fondo Oscuro) --}}
    <div
        x-show="show"
        class="fixed inset-0 transform transition-opacity"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-70"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-70"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-moto-black opacity-70 z-40" @click="{{ $closeable ? 'show = false' : '' }}"></div>
    </div>

    {{-- Contenido del Modal --}}
    <div
        x-show="show"
        class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 z-50"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div {{ $attributes->merge(['class' => "relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full $maxWidthClasses"]) }}>
            <div class="bg-white p-6">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="justify-end p-6 pt-0 sm:flex">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
