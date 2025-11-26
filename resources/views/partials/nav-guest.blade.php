<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex-shrink-0 flex items-center group">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="bg-moto-red text-white p-2 rounded-lg group-hover:rotate-12 transition transform duration-300 shadow-lg shadow-red-500/30">
                        <i class="fas fa-motorcycle text-2xl"></i>
                    </div>
                    <span class="text-moto-black text-2xl font-black tracking-tight group-hover:text-moto-red transition duration-300">
                        Moto<span class="text-moto-red">Rápido</span>
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('appointments.create') }}" class="text-gray-600 hover:text-moto-red font-medium transition duration-150 px-3 py-2">
                    Agendar Cita
                </a>

                @auth
                    <div class="flex items-center space-x-4 ml-4">
                        <span class="text-sm text-gray-500">Hola, <span class="font-bold text-moto-black">{{ Auth::user()->firstname }}</span></span>

                        <a href="{{ route('dashboard') }}" class="bg-moto-black text-white hover:bg-gray-800 px-5 py-2.5 rounded-full text-sm font-bold transition duration-300 shadow-lg transform hover:-translate-y-0.5">
                            Ir al Panel <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @else
                    <div class="flex items-center space-x-3 ml-4 border-l border-gray-200 pl-6">
                        <a href="{{ route('login') }}" class="text-gray-900 hover:text-moto-red font-semibold text-sm px-4 py-2 transition duration-150">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" class="bg-moto-red text-white hover:bg-red-700 px-5 py-2.5 rounded-full text-sm font-bold transition duration-300 shadow-lg shadow-red-500/30 transform hover:-translate-y-0.5 hover:shadow-red-500/50">
                            Registrarse
                        </a>
                    </div>
                @endauth
            </div>

            <div class="-mr-2 flex md:hidden">
                <button @click="open = !open" type="button" class="text-gray-500 hover:text-moto-red focus:outline-none p-2 rounded-md transition duration-150">
                    <span class="sr-only">Menú</span>
                    <i :class="{ 'hidden': open, 'block': !open }" class="fas fa-bars h-6 w-6"></i>
                    <i :class="{ 'hidden': !open, 'block': open }" class="fas fa-times h-6 w-6"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-gray-100 absolute w-full shadow-xl"
         x-cloak
         @click.away="open = false">

        <div class="px-4 pt-4 pb-6 space-y-3">
            <a href="{{ route('appointments.create') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-moto-red hover:bg-red-50 transition">
                <i class="fas fa-calendar-plus mr-3 text-moto-red"></i> Agendar Cita
            </a>

            @auth
                <div class="border-t border-gray-100 my-2 pt-2">
                    <div class="px-4 py-2 mb-2">
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Usuario</p>
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg text-base font-medium bg-moto-black text-white shadow-md text-center">
                        Ir al Dashboard
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-100 mt-2">
                    <a href="{{ route('login') }}" class="flex justify-center items-center px-4 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Ingresar
                    </a>
                    <a href="{{ route('register') }}" class="flex justify-center items-center px-4 py-3 rounded-lg bg-moto-red text-white font-bold hover:bg-red-700 transition shadow-md">
                        Registrarse
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
