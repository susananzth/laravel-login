@props([
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
    'icon' => null,
])

@php
    $baseClasses = "inline-flex items-center justify-center px-6 py-3 border text-sm font-bold rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95";

    $variants = [
        'primary' => 'bg-moto-red text-white border-transparent hover:bg-red-700 focus:ring-moto-red shadow-lg shadow-red-300/50',
        'secondary' => 'bg-white text-moto-black border-moto-black hover:bg-gray-50 focus:ring-moto-black',
        'danger' => 'bg-red-600 text-white border-transparent hover:bg-red-700 focus:ring-red-500 shadow-lg shadow-red-300/50',
        'outline' => 'bg-transparent text-moto-black border-moto-black hover:bg-moto-black hover:text-white focus:ring-moto-black',
    ];

    $buttonClasses = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $buttonClasses]) }}
    @if($disabled) disabled @endif
    {{-- Esto deshabilita el botón automáticamente mientras Livewire carga --}}
    wire:loading.attr="disabled"
    wire:loading.class="opacity-75 cursor-wait"
>
    {{--
        SPINNER INTELIGENTE:
        Solo se muestra cuando Livewire está cargando (wire:loading).
    --}}
    <span wire:loading class="animate-spin mr-2">
        <i class="fas fa-spinner"></i>
    </span>

    {{ $slot }}

    {{-- Icono estático (opcional) --}}
    @if($icon)
        <i class="{{ $icon }} ml-2" wire:loading.remove></i>
    @endif
</button>
