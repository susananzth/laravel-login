<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Definición de Permisos (Keys técnicos)
        $permissions = [
            // Usuarios
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Roles
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            // Servicios
            'services.view', 'services.create', 'services.edit', 'services.delete',
            // Citas
            'appointments.view_all', // Ver todo (Admin)
            'appointments.view_own', // Ver propio (Cliente/Técnico)
            'appointments.create',
            'appointments.be_assigned',
            'appointments.edit',     // Reagendar
            'appointments.cancel',
            'appointments.assign',   // Asignar técnico
            'appointments.complete', // Finalizar trabajo
            // Reportes
            'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 2. Roles y Asignación

        // ADMIN: Todo (Superpoder)
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // TÉCNICO
        $roleTech = Role::create(['name' => 'technician']);
        $roleTech->givePermissionTo([
            'appointments.view_own',
            'appointments.be_assigned',
            'appointments.complete',
            'services.view' // Para ver el catálogo
        ]);

        // CLIENTE
        $roleClient = Role::create(['name' => 'client']);
        $roleClient->givePermissionTo([
            'appointments.view_own',
            'appointments.create',
            'appointments.cancel'
        ]);

        // 3. Usuarios
        $admin = User::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'MotoRapido',
            'email' => 'admin@motorapido.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($roleAdmin);

        // Técnicos y Clientes de prueba...
        User::factory(3)->create()->each(fn($u) => $u->assignRole($roleTech));
        User::factory(3)->create()->each(fn($u) => $u->assignRole($roleClient));

        // Servicios
        Service::create(['name' => 'Cambio de Aceite', 'price' => 50.00, 'duration_minutes' => 30]);
        Service::create(['name' => 'Revisión General', 'price' => 80.00, 'duration_minutes' => 60]);
        Service::create(['name' => 'Reparación Motor', 'price' => 250.00, 'duration_minutes' => 240]);
    }
}
