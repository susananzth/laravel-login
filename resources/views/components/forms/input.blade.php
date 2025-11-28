@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'required' => false,
    'placeholder' => null,
    'wireModel' => null, // Mantenemos por compatibilidad, pero preferimos usar atributos directos
    'icon' => null,
    'viewable' => false, // Solo para passwords
])

@php
    $inputId = $name ?: uniqid('input_');
    // Detectar error: busca en la bolsa de errores por el 'name' o por el 'wireModel'
    $errorKey = $name ?: $wireModel; 
    $hasError = $errorKey && $errors->has($errorKey);

    $baseClasses = "w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-moto-red focus:border-transparent transition duration-200 bg-white text-moto-black placeholder-gray-400 outline-none disabled:bg-gray-100 disabled:text-gray-500";
    $errorClasses = "border-red-500 focus:ring-red-500";
    $normalClasses = "border-gray-300 focus:ring-moto-red";

    $inputClasses = $baseClasses . ' ' . ($hasError ? $errorClasses : $normalClasses);

    if ($icon) $inputClasses .= ' pl-11';
    if ($viewable) $inputClasses .= ' pr-11';
@endphp

<div class="space-y-2" x-data="{ showPassword: false }">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-moto-black">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} {{ $hasError ? 'text-red-400' : 'text-gray-400' }}"></i>
            </div>
        @endif

        {{-- Lógica para el Input --}}
        <input
            id="{{ $inputId }}"
            type="{{ $type === 'password' ? 'text' : $type }}" 
            x-bind:type="{{ $type === 'password' && $viewable ? "showPassword ? 'text' : 'password'" : "'$type'" }}"
            
            @if($name) name="{{ $name }}" @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif

            @if($wireModel) wire:model="{{ $wireModel }}" @endif

            {{ $attributes->merge(['class' => $inputClasses]) }}
        >

        {{-- Botón Ojo Password --}}
        @if($type === 'password' && $viewable)
            <button
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-moto-red transition duration-200"
                @click="showPassword = !showPassword"
                tabindex="-1"
            >
                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
        @endif
        
        {{-- Slot para poner cosas extra (como el spinner de carga) --}}
        {{ $slot }}
    </div>

    @if($hasError)
        <p class="text-red-500 text-sm font-medium mt-1">
            {{ $errors->first($errorKey) }}
        </p>
    @endif
</div>