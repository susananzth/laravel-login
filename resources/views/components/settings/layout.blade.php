<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-8">

            <aside class="w-full md:w-64 shrink-0">
                <ul role="list" class="space-y-1">
                    <li>
                        <a href="{{ route('settings.profile') }}" wire:navigate
                            class="{{ request()->routeIs('settings.profile') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
                            group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
                            <i class="fas fa-user shrink-0 w-6 text-center {{ request()->routeIs('settings.profile') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Perfil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.password') }}" wire:navigate
                            class="{{ request()->routeIs('settings.password') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
                            group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
                            <i class="fas fa-lock shrink-0 w-6 text-center {{ request()->routeIs('settings.password') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Contraseña
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="flex-1 bg-white p-8 shadow rounded-lg border border-gray-100">
                <div class="mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-moto-black">{{ $heading }}</h2>
                    <p class="text-gray-500 text-sm">{{ $subheading }}</p>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</div>
