<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions (Permisos)
        // Usaremos convenciones claras: {action}-{resource}
        $permissions = [
            // User/Role Management (Solo para SuperAdmin)
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'manage-roles',

            // Employee Management
            'view-employees', 'create-employees', 'edit-employees', 'delete-employees',

            // Client Management
            'view-clients', 'create-clients', 'edit-clients', 'delete-clients',

            // Appointment Management
            'view-appointments', 'create-appointments', 'reschedule-appointments',
            'cancel-appointments', 'complete-appointments', 'view-own-appointments',
            'edit-own-appointments', 'cancel-own-appointments', 'view-own-appointments',

            // Reports
            'view-reports',
        ];
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 2. Define Roles and Assign Permissions (Asignar Permisos a Roles)

        // SuperAdmin: Máximo control
        $superAdminRole = Role::findOrCreate('super_admin');
        $superAdminRole->givePermissionTo(Permission::all());

        // Assistant: Gestión de Citas, Clientes y vista de Empleados
        $assistantRole = Role::findOrCreate('assistant');
        $assistantRole->givePermissionTo([
            'view-employees', 'view-clients', 'create-clients', 'edit-clients',
            'view-appointments', 'create-appointments', 'reschedule-appointments',
            'cancel-appointments', 'complete-appointments',
            'view-reports',
        ]);
        // Employee: Solo ver su calendario y sus citas
        $employeeRole = Role::findOrCreate('employee');
        $employeeRole->givePermissionTo([
            // Permiso para marcar como completada/editar notas
            'view-own-appointments', 'edit-own-appointments'
        ]);

        // Registered Client: Solo ver/cancelar sus propias citas
        $clientRole = Role::findOrCreate('registered_client');
        $clientRole->givePermissionTo(['view-own-appointments', 'cancel-own-appointments']);

        // 3. Crear un SuperAdmin inicial
        // Asegúrate de que este usuario tenga la contraseña hasheada
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'username' => 'supersu',
            'phone' => '123456789',
            'email' => 'admin@susananzth.com',
            'password' => bcrypt('password'),
        ])->assignRole($superAdminRole);
    }
}
