@props([
    'name' => '',
    'label' => '',
    'description' => '',
    'checked' => false,
    'value' => '1',
    'wireModel' => null,
    'error' => null,
    'disabled' => false,
])

@php
    $checkboxId = $name ?: uniqid('checkbox_');
    $hasError = $error || ($name && $errors->has($name));

    $baseClasses = "w-4 h-4 text-moto-red bg-white border-gray-300 rounded focus:ring-moto-red focus:ring-2 transition duration-200";
    $errorClasses = "border-red-500 text-red-500";
    $normalClasses = "border-gray-300 text-moto-red";

    $checkboxClasses = $baseClasses . ' ' . ($hasError ? $errorClasses : $normalClasses);
@endphp

<div class="flex items-start space-x-3" x-data="{ isChecked: {{ $checked ? 'true' : 'false' }} }">
    <div class="flex items-center h-5 mt-0.5">
        <input
            id="{{ $checkboxId }}"
            name="{{ $name }}"
            type="checkbox"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($wireModel) wire:model="{{ $wireModel }}" @endif
            @if($disabled) disabled @endif
            x-model="isChecked"
            {{ $attributes->merge(['class' => $checkboxClasses]) }}
        >
    </div>

    <div class="flex flex-col">
        <label for="{{ $checkboxId }}" class="text-sm font-semibold text-moto-black cursor-pointer select-none hover:text-gray-700 transition duration-200">
            {{ $label }}
        </label>

        @if($description)
            <p class="text-sm text-gray-600 mt-1">
                {{ $description }}
            </p>
        @endif
    </div>

    @if($hasError)
        <p class="mt-1 text-red-500 text-sm font-medium">
            {{ $error ?: ($name ? $errors->first($name) : '') }}
        </p>
    @endif
</div>
