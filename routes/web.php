<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Booking\CreateAppointment;
use App\Livewire\Admin\ManageUsers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // 3. Rutas para CLIENTES
    Route::middleware(['role:client'])->group(function () {
        Route::get('/appointments/create', CreateAppointment::class)
            ->name('appointments.create');
    });

    // 4. Rutas para ADMINISTRADORES
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users', ManageUsers::class)->name('admin.users');

        // Aquí podrías agregar reportes o configuraciones extra
    });
});

require __DIR__.'/auth.php';
