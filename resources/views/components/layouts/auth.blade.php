<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-white font-sans flex flex-col min-h-screen">
        @include('partials.nav-guest')

        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>

        @include('partials.footer-guest')

        @fluxScripts

        @livewireScripts

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
