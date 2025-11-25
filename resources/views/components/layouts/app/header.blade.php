<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">

    {{-- Botón Hamburguesa (Solo Móvil) --}}
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Abrir sidebar</span>
        <i class="fas fa-bars text-2xl"></i>
    </button>

    {{-- Separador (Móvil) --}}
    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        {{-- Espaciador / Buscador --}}
        <div class="relative flex flex-1 items-center">
            <h1 class="text-xl font-bold text-moto-black hidden md:block">
               {{ $title ?? 'Panel de Control' }}
            </h1>
        </div>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            {{-- Separador --}}
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            {{-- Dropdown de Perfil --}}
            <div class="relative" x-data="{ open: false }">
                <button type="button" class="-m-1.5 flex items-center p-1.5" @click="open = !open" @click.outside="open = false">
                    <span class="sr-only">Abrir menú de usuario</span>

                    {{-- Avatar con iniciales --}}
                    <div class="h-8 w-8 rounded-full bg-moto-black text-white flex items-center justify-center text-sm font-bold border-2 border-transparent hover:border-moto-red transition">
                        {{ auth()->user()->initials() }}
                    </div>

                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">{{ auth()->user()->firstname . ' ' . auth()->user()->lastname }}</span>
                        <i class="fas fa-chevron-down ml-2 text-xs text-gray-400"></i>
                    </span>
                </button>

                {{-- Menú Desplegable --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                     style="display: none;">

                    <div class="px-3 py-2 border-b border-gray-100 mb-1 lg:hidden">
                        <span class="block text-xs font-bold text-gray-500">{{ auth()->user()->email }}</span>
                    </div>

                    <a href="{{ route('settings.profile') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50 hover:text-moto-red">
                        <i class="fas fa-user-circle mr-2 w-4"></i> Tu Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-1 text-sm leading-6 text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2 w-4"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
