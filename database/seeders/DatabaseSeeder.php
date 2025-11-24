<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Crear Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleTech = Role::create(['name' => 'technician']);
        $roleClient = Role::create(['name' => 'client']);

        // 2. Crear Admin
        $admin = User::factory()->create([
            'name' => 'Admin MotoRapido',
            'email' => 'me@susananzth.com',
            'password' => bcrypt('Susana.29'), // Cambiar en prod
        ]);
        $admin->assignRole($roleAdmin);

        // 3. Crear Técnicos (3 según el caso de estudio)
        User::factory(3)->create()->each(function ($user) use ($roleTech) {
            $user->assignRole($roleTech);
        });

        // 3. Crear Clientes (3 según el caso de estudio)
        User::factory(3)->create()->each(function ($user) use ($roleClient) {
            $user->assignRole($roleClient);
        });

        // 4. Servicios Base
        Service::create(['name' => 'Cambio de Aceite', 'price' => 50.00, 'duration_minutes' => 30]);
        Service::create(['name' => 'Revisión General', 'price' => 80.00, 'duration_minutes' => 60]);
        Service::create(['name' => 'Reparación Motor', 'price' => 250.00, 'duration_minutes' => 240]);
    }
}
