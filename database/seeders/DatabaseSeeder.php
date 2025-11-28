<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB Transaction: Si algo falla, no se inserta nada.
        DB::beginTransaction();

        try {
            $this->setupPermissionsAndRoles();
            $this->setupUsers();
            $services = $this->setupServices(); // Retorna la colección de servicios
            $this->setupAppointments($services);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // -------------------------------------------------------------------------
    // BLOQUE 1: Permisos y Roles
    // -------------------------------------------------------------------------
    private function setupPermissionsAndRoles()
    {
        // Definición de Permisos (Keys técnicos)
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

        // Roles y Asignación

        // ADMIN: Todo (Superpoder)
        $roleAdmin = Role::create(['name' => 'Administrador']);
        $roleAdmin->givePermissionTo(Permission::all());

        // TÉCNICO
        $roleTech = Role::create(['name' => 'Ténico']);
        $roleTech->givePermissionTo([
            'appointments.view_own',
            'appointments.be_assigned',
            'appointments.edit',
            'appointments.complete',
            'services.view' // Para ver el catálogo
        ]);

        // CLIENTE
        $roleClient = Role::create(['name' => 'Cliente']);
        $roleClient->givePermissionTo([
            'appointments.view_own',
            'appointments.create',
            'appointments.edit',
            'appointments.cancel'
        ]);
    }
    // -------------------------------------------------------------------------
    // BLOQUE 2: Usuarios
    // -------------------------------------------------------------------------
    private function setupUsers()
    {
        // Admin
        $admin = User::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'MotoRapido',
            'email' => 'admin@motorapido.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Administrador');

        // Técnicos (5)
        User::factory(5)->create()->each(fn($u) => $u->assignRole('Ténico'));

        // Clientes (150)
        User::factory(150)->create()->each(fn($u) => $u->assignRole('Cliente'));
    }

    // -------------------------------------------------------------------------
    // BLOQUE 3: Servicios
    // -------------------------------------------------------------------------
    private function setupServices()
    {
        $servicesList = [
            [
                'name' => 'Cambio de Aceite y Filtro',
                'description' => 'Drenaje de aceite usado, cambio de filtro de aceite, arandela y llenado con aceite sintético o semi-sintético según especificación.',
                'price' => 50.00,
                'duration_minutes' => 30
            ],
            [
                'name' => 'Mantenimiento Preventivo (Tune-up)',
                'description' => 'Revisión general, ajuste de frenos, lubricación de cadena, limpieza de filtro de aire, revisión de luces y presión de neumáticos.',
                'price' => 120.00,
                'duration_minutes' => 90
            ],
            [
                'name' => 'Cambio de Kit de Arrastre',
                'description' => 'Sustitución de piñón de ataque, catalina (corona) y cadena. Incluye tensado y lubricación final.',
                'price' => 90.00,
                'duration_minutes' => 60
            ],
            [
                'name' => 'Limpieza de Carburador / Inyectores',
                'description' => 'Desmontaje y limpieza profunda con ultrasonido para eliminar residuos y asegurar una mezcla óptima de combustible.',
                'price' => 150.00,
                'duration_minutes' => 120
            ],
            [
                'name' => 'Reparación de Motor (Ajuste)',
                'description' => 'Desarmado parcial o total del motor para cambio de anillos, pistones o válvulas. Requiere diagnóstico previo.',
                'price' => 450.00,
                'duration_minutes' => 360 // 6 horas
            ],
            [
                'name' => 'Cambio de Pastillas de Freno',
                'description' => 'Reemplazo de pastillas delanteras o traseras, limpieza de cálipers y purgado de líquido si es necesario.',
                'price' => 40.00,
                'duration_minutes' => 45
            ],
            [
                'name' => 'Mantenimiento de Suspensión',
                'description' => 'Cambio de retenes y aceite de horquillas telescópicas. Revisión de fugas.',
                'price' => 180.00,
                'duration_minutes' => 150
            ],
            [
                'name' => 'Diagnóstico Sistema Eléctrico',
                'description' => 'Revisión de cableado, batería, regulador de voltaje y estator para detectar fallas de carga o encendido.',
                'price' => 70.00,
                'duration_minutes' => 60
            ],
            [
                'name' => 'Cambio de Neumáticos',
                'description' => 'Desmontaje de rueda, cambio de llanta y balanceo estático (si aplica). No incluye costo del neumático.',
                'price' => 35.00,
                'duration_minutes' => 40
            ],
            [
                'name' => 'Regulación de Válvulas',
                'description' => 'Ajuste de la holgura de las válvulas de admisión y escape para recuperar potencia y reducir ruidos.',
                'price' => 100.00,
                'duration_minutes' => 120
            ],
        ];

        $createdServices = collect();
        foreach ($servicesList as $data) {
            $createdServices->push(Service::create($data));
        }
        return $createdServices;
    }

    // -------------------------------------------------------------------------
    // BLOQUE 4: Citas
    // -------------------------------------------------------------------------
    private function setupAppointments($services)
    {
        // Obtener IDs para no hacer queries dentro del loop (Performance)
        // Usamos Role para filtrar, asumiendo que Spatie funciona correctamente
        $clientIds = User::role('Cliente')->pluck('id')->toArray();
        $techIds = User::role('Ténico')->pluck('id')->toArray();

        // Validar que existan usuarios antes de continuar
        if (empty($clientIds) || empty($techIds)) {
            return;
        }

        // Definir rango de fechas: 3 meses atrás hasta 15 días adelante
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now()->addDays(15);
        $currentDate = $startDate->copy();

        $appointmentsData = [];

        while ($currentDate <= $endDate) {
            // Saltamos domingos si el taller no abre (opcional, aquí asumo Lunes-Sábado)
            if ($currentDate->isSunday()) {
                $currentDate->addDay();
                continue;
            }

            // Entre 3 y 6 citas por día
            $dailyAppointments = rand(3, 6);

            for ($i = 0; $i < $dailyAppointments; $i++) {
                $service = $services->random();
                
                // Horario aleatorio laboral (09:00 a 17:00)
                $hour = rand(9, 17);
                $minute = [0, 15, 30, 45][rand(0, 3)];
                
                $scheduledAt = $currentDate->copy()->setTime($hour, $minute);
                
                // Determinar estado basado en si la fecha ya pasó
                $isPast = $scheduledAt->lt(Carbon::now());
                
                if ($isPast) {
                    // 80% completadas, 20% canceladas en el pasado
                    $status = (rand(1, 10) > 2) ? 'completed' : 'cancelled';
                    $finishedAt = ($status === 'completed') 
                        ? $scheduledAt->copy()->addMinutes($service->duration_minutes) 
                        : null;
                } else {
                    // Futuro: pending o confirmed
                    $status = (rand(1, 10) > 5) ? 'confirmed' : 'pending';
                    $finishedAt = null;
                }

                $appointmentsData[] = [
                    'user_id' => $clientIds[array_rand($clientIds)],
                    'technician_id' => $techIds[array_rand($techIds)],
                    'service_id' => $service->id,
                    'scheduled_at' => $scheduledAt,
                    'finished_at' => $finishedAt,
                    'status' => $status,
                    'notes' => 'Cita generada automáticamente por seeder.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $currentDate->addDay();
        }

        // Insert masivo por chunks para no saturar la memoria si fueran muchos datos
        foreach (array_chunk($appointmentsData, 50) as $chunk) {
            DB::table('appointments')->insert($chunk);
        }
    }
}
