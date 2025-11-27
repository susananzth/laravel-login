{{-- Dashboard --}}
<li>
    <a href="{{ route('dashboard') }}" wire:navigate
       class="{{ request()->routeIs('dashboard') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-home shrink-0 w-6 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Dashboard
    </a>
</li>
@canany(['appointments.view_all', 'appointments.view_own'])
<li>
    <a href="{{ route('admin.appointments') }}" wire:navigate
       class="{{ request()->routeIs('admin.appointments') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-calendar-days shrink-0 w-6 text-center {{ request()->routeIs('admin.appointments') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Gestión Citas
    </a>
</li>
@endcanany
@can('services.view')
<li>
    <a href="{{ route('admin.services') }}" wire:navigate
       class="{{ request()->routeIs('admin.services') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-screwdriver shrink-0 w-6 text-center {{ request()->routeIs('admin.services') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Servicios
    </a>
</li>
@endcan
@can('users.view')
<li>
    <a href="{{ route('admin.users') }}" wire:navigate
       class="{{ request()->routeIs('admin.users') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-users shrink-0 w-6 text-center {{ request()->routeIs('admin.users') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Usuarios
    </a>
</li>
@endcan
@can('roles.view')
<li>
    <a href="{{ route('admin.roles') }}" wire:navigate
       class="{{ request()->routeIs('admin.roles') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-user-tag shrink-0 w-6 text-center {{ request()->routeIs('admin.roles') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Roles
    </a>
</li>
@endcan
@can('appointments.create')
<li>
    <a href="{{ route('appointments.create') }}" wire:navigate
       class="{{ request()->routeIs('appointments.create') ? 'bg-moto-red text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:text-white hover:bg-zinc-800' }}
              group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition duration-200">
        <i class="fas fa-calendar-plus shrink-0 w-6 text-center {{ request()->routeIs('appointments.create') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        Reservar cita
    </a>
</li>
@endcan

