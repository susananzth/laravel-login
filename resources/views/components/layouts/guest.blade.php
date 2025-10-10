<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-white font-sans">
        @include('partials.nav-guest')

        <main>
            {{ $slot }}
        </main>

        @include('partials.footer-guest')

        @fluxScripts

        <script src="//unpkg.com/alpinejs" defer></script>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
