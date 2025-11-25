@props(['color' => 'gray', 'label'])

@php
    $colors = [
        'red' => 'bg-red-100 text-red-800 border-red-200',
        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
        'green' => 'bg-green-100 text-green-800 border-green-200',
        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
        'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
        'moto' => 'bg-moto-black text-white border-moto-black', // Variante especial
    ];

    $classes = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border " . ($colors[$color] ?? $colors['gray']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label ?? $slot }}
</span>
