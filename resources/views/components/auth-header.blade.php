@props([
    'title',
    'description',
])

<div class="text-center mb-8">
    <x-app-logo-icon />
    <h2 class="text-2xl font-bold text-moto-black mt-2">{{ $title }}</h2>
    <p class="text-gray-600 mt-2">{{ $description }}</p>
</div>
