<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <i class="fas fa-motorcycle text-moto-red text-2xl mr-2"></i>
                    <span class="text-moto-black text-xl font-bold tracking-wider">Mi Taller</span>
                </a>
            </div>

            <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                <a href="#" class="bg-moto-red text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-bold transition duration-150 shadow-lg shadow-red-300/50">
                    Agendar Cita
                </a>
                <a href="{{ route('login') }}" class="text-moto-black hover:text-moto-red px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="text-moto-black border border-moto-black hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                    Registrarse
                </a>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" type="button" class="bg-gray-100 inline-flex items-center justify-center p-2 rounded-md text-moto-black hover:text-moto-red hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-moto-red" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Abrir menú principal</span>
                    <i :class="{ 'hidden': open, 'block': !open }" class="fas fa-bars h-6 w-6"></i>
                    <i :class="{ 'hidden': !open, 'block': open }" class="fas fa-times h-6 w-6"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open"
        x-transition:enter="duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="sm:hidden origin-top absolute w-full bg-white shadow-lg"
        id="mobile-menu"
        x-cloak>

        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#" class="bg-moto-red text-white block px-3 py-2 rounded-md text-base font-medium">Agendar Cita</a>
            <a href="{{ route('login') }}" class="text-moto-black hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="text-moto-black hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">Registrarse</a>
        </div>
    </div>
</nav>
