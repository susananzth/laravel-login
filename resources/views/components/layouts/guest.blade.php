<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-white font-sans flex flex-col min-h-screen antialiased text-gray-900">

        @include('partials.nav-guest')

        <main class="flex-grow">
            {{ $slot }}
        </main>

        @include('partials.footer-guest')

        @fluxScripts

        <x-toast-notification />
        @livewireScripts

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
