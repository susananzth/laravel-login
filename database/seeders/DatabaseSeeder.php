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
        // 1. Permisos del Sistema (Granularidad fina)
        $permissions = [
            // Usuarios y Roles
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',

            // Citas
            'appointments.view_all', // Ver todas las citas (Admin/Recepcionista)
            'appointments.view_own', // Ver mis citas (Cliente/Técnico)
            'appointments.create',
            'appointments.edit',     // Reprogramar/Editar
            'appointments.cancel',

            // Gestión Técnica
            'appointments.assign',   // Asignar técnico
            'appointments.complete', // Marcar como completada
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 2. Roles Base y sus Permisos

        // ADMIN: Todo
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // TÉCNICO: Ve sus citas y las completa
        $roleTech = Role::create(['name' => 'technician']);
        $roleTech->givePermissionTo([
            'appointments.view_own',
            'appointments.complete'
        ]);

        // CLIENTE: Ve sus citas, crea y cancela (si no es tarde)
        $roleClient = Role::create(['name' => 'client']);
        $roleClient->givePermissionTo([
            'appointments.view_own',
            'appointments.create',
            'appointments.cancel'
        ]);

        // 3. Usuarios Iniciales
        $admin = User::factory()->create([
            'firstname' => 'Admin MotoRapido',
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
