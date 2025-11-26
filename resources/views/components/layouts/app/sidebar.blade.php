{{-- SIDEBAR MÓVIL (Off-canvas) --}}
<div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    {{-- Backdrop (Fondo oscuro transparente) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-moto-black/80"></div>

    <div class="fixed inset-0 flex">
        {{-- Panel deslizante --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex w-full max-w-xs flex-1">

            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                    <span class="sr-only">Cerrar menú</span>
                    <i class="fas fa-times text-white text-xl"></i>
                </button>
            </div>

            {{-- Contenido del Sidebar Móvil --}}
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-zinc-900 px-6 pb-4 ring-1 ring-white/10">
                <div class="flex h-16 shrink-0 items-center gap-3">
                    <x-app-logo-icon class="text-moto-red text-2xl" />
                    <span class="text-white font-bold text-lg tracking-wider">Mi Taller</span>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                @include('components.layouts.nav-links')
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- SIDEBAR DESKTOP (Estático) --}}
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-zinc-900 px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center gap-3 mt-4 mb-2">
            <div class="flex h-10 w-10 items-center justify-center bg-white/5 rounded-lg border border-white/10">
                <x-app-logo-icon class="text-moto-red text-xl" />
            </div>
            <div class="leading-none">
                <span class="block text-white text-xl font-bold tracking-wider">Mi Taller</span>
                <span class="block text-xs text-zinc-500 mt-1 font-medium">Panel Admin</span>
            </div>
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        @include('components.layouts.nav-links')
                    </ul>
                </li>

                {{-- Menú de usuario al pie del sidebar --}}
                <li class="mt-auto">
                    <a href="{{ route('settings.profile') }}" class="group -mx-2 flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-zinc-800 hover:text-white transition">
                        <i class="fas fa-cog text-gray-400 group-hover:text-white shrink-0 mt-1"></i>
                        Configuración
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
