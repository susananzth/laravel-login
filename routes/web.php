<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Booking\CreateAppointment;
use App\Livewire\Admin\ManageAppointments;
use App\Livewire\Admin\ManageRoles;
use App\Livewire\Admin\ManageServices;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Admin\Reports;
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::view('/project-specs', 'project-specs')->name('project.specs');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        $stats = [];
        if (auth()->user()->hasAnyPermission(['appointments.view_all', 'appointments.be_assigned'])) {
            $stats = [
                'today' => Appointment::whereDate('scheduled_at', now())->count(),
                'pending' => Appointment::where('status', 'pending')->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    })->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/appointments/create', CreateAppointment::class)->name('appointments.create');

    Route::get('/admin/appointments', ManageAppointments::class)->name('admin.appointments');
    Route::get('/admin/services', ManageServices::class)->name('admin.services');
    Route::get('/admin/roles', ManageRoles::class)->name('admin.roles');
    Route::get('/admin/users', ManageUsers::class)->name('admin.users');
    Route::get('/admin/reports', Reports::class)->name('admin.reports');
});

Route::get('/test-403', function () { abort(403); });
Route::get('/test-404', function () { abort(404); });
Route::get('/test-500', function () { abort(500); });

require __DIR__.'/auth.php';
