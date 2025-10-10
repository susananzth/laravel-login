@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'wireModel' => null,
    'error' => null,
    'icon' => null,
    'viewable' => false, // Solo para passwords
])

@php
    $inputId = $name ?: uniqid('input_');
    $hasError = $error || ($name && $errors->has($name));

    $baseClasses = "w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-moto-red focus:border-transparent transition duration-200 bg-white text-moto-black placeholder-gray-400";
    $errorClasses = "border-red-500 focus:ring-red-500";
    $normalClasses = "border-gray-300 focus:ring-moto-red";

    $inputClasses = $baseClasses . ' ' . ($hasError ? $errorClasses : $normalClasses);

    // Padding adicional para íconos
    if ($icon) {
        $inputClasses .= ' pl-11';
    }
    if ($viewable) {
        $inputClasses .= ' pr-11';
    }
@endphp

<div class="space-y-2" x-data="{ showPassword: false }">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-moto-black">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
        @endif

        @if($type === 'password' && $viewable)
            <input
                id="{{ $inputId }}"
                name="{{ $name }}"
                :type="showPassword ? 'text' : 'password'"
                @if($wireModel) wire:model="{{ $wireModel }}" @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
                {{ $attributes->merge(['class' => $inputClasses]) }}
            >

            <button
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-moto-red transition duration-200"
                @click="showPassword = !showPassword"
            >
                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
        @else
            <input
                id="{{ $inputId }}"
                name="{{ $name }}"
                type="{{ $type }}"
                @if($wireModel) wire:model="{{ $wireModel }}" @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
                {{ $attributes->merge(['class' => $inputClasses]) }}
            >
        @endif
    </div>

    @if($hasError)
        <p class="text-red-500 text-sm font-medium">
            {{ $error ?: ($name ? $errors->first($name) : '') }}
        </p>
    @endif
</div>
