{{-- Dashboard --}}
<li>
    <a href="{{ route('dashboard') }}" wire:navigate
       class="{{ request()->routeIs('dashboard') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-home shrink-0 w-6 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Dashboard
    </a>
</li>

@role('admin')
<li>
    <a href="{{ route('admin.users') }}" wire:navigate
       class="{{ request()->routeIs('admin.users') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-users shrink-0 w-6 text-center {{ request()->routeIs('admin.users') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Usuarios
    </a>
</li>
<li>
    <a href="{{ route('admin.appointments') }}" wire:navigate
       class="{{ request()->routeIs('admin.appointments') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-calendar-days shrink-0 w-6 text-center {{ request()->routeIs('admin.appointments') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Gestión Citas
    </a>
</li>
@endrole

@role('client')
<li>
    <a href="{{ route('appointments.create') }}" wire:navigate
       class="{{ request()->routeIs('appointments.create') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-calendar-plus shrink-0 w-6 text-center {{ request()->routeIs('appointments.create') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Reservar
    </a>
</li>
@endrole
