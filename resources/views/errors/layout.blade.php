<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - MotoRápido</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Fallback por si Tailwind falla al compilar en producción */
            body { font-family: 'Figtree', sans-serif; }
            .bg-moto-red { background-color: #DC2626; }
            .text-moto-red { color: #DC2626; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800 h-screen flex flex-col items-center justify-center overflow-hidden relative">

        <div class="absolute top-0 left-0 -mt-20 -ml-20 w-64 h-64 rounded-full bg-moto-red opacity-5 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 -mb-20 -mr-20 w-80 h-80 rounded-full bg-blue-500 opacity-5 blur-3xl"></div>

        <div class="relative z-10 max-w-xl w-full px-6 text-center">

            <div class="mb-6">
                <span class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white shadow-xl">
                    @yield('icon')
                </span>
            </div>

            <h1 class="text-6xl font-extrabold text-gray-900 tracking-tight mb-2">
                @yield('code')
            </h1>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                @yield('message')
            </h2>

            <p class="text-gray-500 mb-10 text-lg">
                @yield('description')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                {{-- Lógica segura para volver --}}
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-3 bg-moto-red hover:bg-red-700 text-white font-bold rounded-lg shadow-lg transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-moto-red">
                        <i class="fas fa-tachometer-alt mr-2"></i> Volver al Panel
                    </a>
                @else
                    <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-3 bg-moto-red hover:bg-red-700 text-white font-bold rounded-lg shadow-lg transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-moto-red">
                        <i class="fas fa-home mr-2"></i> Ir al Inicio
                    </a>
                @endauth

                <button onclick="window.history.back()" class="w-full sm:w-auto px-8 py-3 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold rounded-lg shadow transition focus:outline-none">
                    <i class="fas fa-arrow-left mr-2"></i> Regresar
                </button>
            </div>
        </div>

        <div class="absolute bottom-6 text-gray-400 text-sm">
            &copy; {{ date('Y') }} SusanaNzth. Todos los derechos reservados.
        </div>
    </body>
</html>
