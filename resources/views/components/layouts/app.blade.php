<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
    <head>
        @include('partials.head')
    </head>
    <body class="h-full font-sans antialiased text-gray-900">

        {{-- Estado global del layout para controlar el menú móvil --}}
        <div x-data="{ sidebarOpen: false }">

            {{-- Sidebar Móvil y Desktop --}}
            @include('components.layouts.app.sidebar')

            {{-- Contenedor Principal --}}
            <div class="lg:pl-72">

                {{-- Header - Barra superior --}}
                @include('components.layouts.app.header')

                {{-- Contenido de la Página --}}
                <main class="py-10">
                    <div class="px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <style>
            .fc-header-toolbar .fc-toolbar .fc-toolbar-ltr {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0 0.5rem;
            }
        </style>

        <x-toast-notification />
        @livewireScripts
    </body>
</html>
