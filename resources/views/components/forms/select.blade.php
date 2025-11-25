@props([
    'name' => '',
    'label' => '',
    'required' => false,
    'wireModel' => null,
    'error' => null,
    'icon' => null,
    'options' => [], // Array de opciones para el select: ['valor' => 'Etiqueta']
    'placeholder' => null, // Opcional: para la primera opción deshabilitada.
])

@php
    $selectId = $name ?: uniqid('select_');
    $hasError = $error || ($name && $errors->has($name));

    $baseClasses = "w-full px-4 py-3 border rounded-lg appearance-none focus:ring-2 focus:ring-moto-red focus:border-transparent transition duration-200 bg-white text-moto-black placeholder-gray-400 outline-[none] cursor-pointer";
    $errorClasses = "border-red-500 focus:ring-red-500";
    $normalClasses = "border-gray-300 focus:ring-moto-red";

    $selectClasses = $baseClasses . ' ' . ($hasError ? $errorClasses : $normalClasses);

    // Padding adicional para íconos
    if ($icon) {
        $selectClasses .= ' pl-11';
    } else {
        $selectClasses .= ' pr-10'; // Ajuste por defecto para el icono de la flecha del select
    }

@endphp

<div class="space-y-2">
    @if($label)
        <label for="{{ $selectId }}" class="block text-sm font-semibold text-moto-black">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
        @endif

        <select
            id="{{ $selectId }}"
            name="{{ $name }}"
            @if($wireModel) wire:model.live="{{ $wireModel }}" @endif
            @if($required) required @endif
            {{ $attributes->merge(['class' => $selectClasses]) }}
        >
            @if($placeholder)
                <option value="" disabled>{{ $placeholder }}</option>
            @endif
            @foreach($options as $value => $optionLabel)
                <option value="{{ $value }}">{{ $optionLabel }}</option>
            @endforeach

            {{ $slot }} {{-- Permite inyectar opciones adicionales manualmente --}}
        </select>

        {{-- Icono de flecha personalizado para evitar la flecha nativa inconsistente --}}
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none z-10">
            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
        </div>
    </div>

    @if($hasError)
        <p class="text-red-500 text-sm font-medium">
            {{ $error ?: ($name ? $errors->first($name) : '') }}
        </p>
    @endif
</div>
