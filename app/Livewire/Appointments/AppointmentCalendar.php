<?php

namespace App\Livewire\Appointments;

use App\Mail\AppointmentNotification;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;

class AppointmentCalendar extends Component
{
    public $selectedAppointment = null;
    public $technician_id;
    public $status;
    public $adminNotes;
    public $showModal = false;

    // Esta función detecta cuando cambia $showModal y avisa al JavaScript
    public function updatedShowModal($value)
    {
        $this->dispatch('show-modal-changed', $value);
    }

    // Para cargar eventos al iniciar o cambiar mes
    #[On('refresh-calendar')]
    public function getEvents()
    {
        $user = Auth::user();
        $query = Appointment::with(['client', 'service', 'technician']);
        $query->whereBetween('scheduled_at', [now()->startOfYear(), now()->endOfYear()]);

        if ($user->hasPermissionTo('appointments.view_all')) {
            // No filtramos nada, ve todo.
        } elseif ($user->hasPermissionTo('appointments.be_assigned')) {
            $query->where('technician_id', $user->id);
        } else {
            $query->where('user_id', $user->id);
        }

        return $query->get()->map(function ($cita) use ($user) {
            // Colores según estado
            $color = match ($cita->status) {
                'pending' => '#F59E0B', // Ambar
                'confirmed' => '#3B82F6', // Azul
                'completed' => '#10B981', // Verde
                'cancelled' => '#EF4444', // Rojo
                default => '#6B7280',
            };

            $title = $user->hasRole('Cliente')
                ? $cita->service->name
                : $cita->client->firstname . ' - ' . $cita->service->name;

            return [
                'id' => $cita->id,
                'title' => $title,
                'start' => $cita->scheduled_at->toIso8601String(),
                'color' => $color,
                'extendedProps' => [
                    'status' => $cita->status,
                    'technician' => $cita->technician?->firstname . ' ' . $cita->technician?->lastname ?? 'Sin asignar',
                    'notes' => $cita->notes
                ]
            ];
        });
    }

    public function updateAppointment()
    {
        abort_unless(auth()->user()->hasAnyPermission(['appointments.be_assigned', 'appointments.assign', 'appointments.complete']), 403);

        $user = Auth::user();
        $appointment = $this->selectedAppointment;

        // Técnicos solo pueden modificar sus propias citas
        if ($user->hasPermissionTo('appointments.be_assigned') && !$user->hasPermissionTo('appointments.assign')) {
            if ($appointment->technician_id !== $user->id) {
                $this->dispatch('app-error', 'Solo puedes modificar tus propias citas asignadas.');
                return;
            }
        }

        // No permitir modificar citas completadas o canceladas
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            $this->dispatch('app-error', 'No se puede modificar una cita ' . __($appointment->status) . '.');
            return;
        }

        $this->validate([
            'status' => [
                'required',
                'string',
                'in:pending,confirmed,in_progress,completed,cancelled',
                'max:20'
            ],
            'technician_id' => [
                'nullable',
                'integer',
                'exists:users,id'
            ],
            'adminNotes' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if ($value && preg_match('/<script|javascript:|onclick|onload|onerror/i', $value)) {
                        $fail('Las notas contienen contenido no permitido.');
                    }
                }
            ]
        ]);

        $currentStatus = $this->selectedAppointment->status;
        $newStatus = $this->status;

        // Validar transiciones permitidas
        /*$allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [], // No se puede cambiar desde completado
            'cancelled' => [], // No se puede cambiar desde cancelado
        ];

        if (!in_array($newStatus, $allowedTransitions[$currentStatus])) {
            $this->dispatch('app-error', 'Transición de estado no permitida de ' . __($currentStatus) . ' a ' . __($newStatus) . '.');
            return;
        }*/

        // Solo administradores pueden cancelar citas confirmadas
        if ($currentStatus === 'confirmed' && $newStatus === 'cancelled') {
            if (!$user->hasPermissionTo('appointments.assign')) {
                $this->dispatch('app-error', 'Solo los administradores pueden cancelar citas confirmadas.');
                return;
            }
        }

        // Validar que se asigne técnico antes de confirmar
        if ($newStatus === 'confirmed' && empty($this->technician_id)) {
            $this->dispatch('app-error', 'Debe asignar un técnico antes de confirmar la cita.');
            return;
        }

        try {
            $this->selectedAppointment->update([
                'technician_id' => $this->technician_id ?: null, // Si viene vacío, null
                'status' => $this->status,
                'notes' => $this->adminNotes ? $this->clean($this->adminNotes) : null
            ]);

            $this->showModal = false;
            $this->dispatch('close-modal');
            // Emitimos evento para refrescar calendario y notificar
            $this->dispatch('refresh-calendar');
            $this->dispatch('notify', 'Cita actualizada correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('app-error', 'Error al actualizar la cita: ' . $e->getMessage());
        }
    }

    // Cargar datos al hacer clic en el calendario
    public function editAppointment($id)
    {
        abort_unless(auth()->user()->hasAnyPermission(['appointments.be_assigned', 'appointments.assign', 'appointments.complete']), 403);

        $this->selectedAppointment = Appointment::findOrFail($id);

        // Validar que la cita no esté completada o cancelada
        if (in_array($this->selectedAppointment->status, ['completed', 'cancelled'])) {
            $this->dispatch('app-error', 'No se puede modificar una cita ' . __($this->selectedAppointment->status) . '.');
            return;
        }

        $user = Auth::user();
        $appointment = $this->selectedAppointment;

        // Clientes solo pueden ver, no editar en el calendario
        if ($user->hasRole('Cliente')) {
            $this->dispatch('app-error', 'No tienes permisos para editar citas desde el calendario.');
            return;
        }

        // Técnicos solo pueden editar sus citas asignadas
        if ($user->hasPermissionTo('appointments.be_assigned') && !$user->hasPermissionTo('appointments.assign')) {
            if ($appointment->technician_id !== $user->id) {
                $this->dispatch('app-error', 'Solo puedes editar tus propias citas asignadas.');
                return;
            }
        }

        $this->technician_id = $this->selectedAppointment->technician_id;
        $this->status = $this->selectedAppointment->status;
        $this->adminNotes = $this->selectedAppointment->notes;

        $this->showModal = true;
        $this->dispatch('open-modal', 'admin-appointment-manager');
    }

    // Método para mover citas (Drag & Drop) - Solo Admin y Cliente (con reglas)
    public function updateAppointmentDate($id, $newDate)
    {
        $appointment = Appointment::findOrFail($id);
        $user = Auth::user();

        if (!strtotime($newDate)) {
            $this->dispatch('app-error', 'Fecha inválida.');
            return;
        }

        // Validaciones rápidas de permisos
        if ($user->hasPermissionTo('appointments.edit') && $appointment->user_id !== $user->id) {
            $this->dispatch('app-error', 'Solo puedes modificar tus propias citas.');
            return;
        }

        // Solo admin puede mover citas de otros
        if ($appointment->user_id !== $user->id && !$user->hasPermissionTo('appointments.assign')) {
            $this->dispatch('app-error', 'No tienes permisos para mover esta cita.');
            return;
        }

        // No permitir mover citas completadas o canceladas
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            $this->dispatch('app-error', 'No se puede reprogramar una cita ' . $appointment->status . '.');
            return;
        }

        $newDateTime = Carbon::parse($newDate);
        $now = Carbon::now();

        // Validar que la nueva fecha no sea en el pasado
        if ($newDateTime->lt($now)) {
            $this->dispatch('app-error', 'No se puede programar una cita en el pasado.');
            return;
        }

        // Validar antelación mínima (24 horas para reprogramación)
        if ($appointment->scheduled_at->diffInHours($now) < 24) {
            $this->dispatch('app-error', 'No se puede reprogramar con menos de 24h de antelación.');
            return;
        }

        // Validar que la nueva fecha tenga al menos 1 hora de antelación
        if ($newDateTime->diffInHours($now) < 1) {
            $this->dispatch('app-error', 'La cita debe programarse con al menos 1 hora de antelación.');
            return;
        }

        // Validar que no sea el mismo horario
        if ($newDateTime->eq($appointment->scheduled_at)) {
            $this->dispatch('app-error', 'La nueva fecha y hora son iguales a la actual.');
            return;
        }

        // Opcional: Validar horario laboral (lunes a viernes, 8am-6pm)
        if (!$this->isBusinessHours($newDateTime)) {
            $this->dispatch('app-error', 'La cita debe programarse en horario laboral (Lunes a Viernes, 8:00 - 18:00).');
            return;
        }

        try {
            $appointment->scheduled_at = Carbon::parse($newDate);
            $appointment->save();

            Mail::to($appointment->client->email)->send(new AppointmentNotification($appointment, 'updated'));

            $this->dispatch('notify', 'Cita reprogramada.');
            $this->dispatch('refresh-calendar');
        } catch (\Exception $e) {
            $this->dispatch('app-error', $e->getMessage());
            $this->dispatch('refresh-calendar'); // Recargar para revertir visualmente
        }
    }

    /**
     * Limpia y sanitiza las notas
     */
    private function clean($notes)
    {
        if (empty($notes)) {
            return null;
        }

        // Eliminar etiquetas HTML peligrosas
        $cleaned = strip_tags($notes);

        // Limitar longitud
        if (strlen($cleaned) > 500) {
            $cleaned = substr($cleaned, 0, 500);
        }

        return $cleaned;
    }

    private function isBusinessHours(Carbon $date)
    {
        // Lunes = 1, Viernes = 5
        $isWeekday = $date->dayOfWeek >= 1 && $date->dayOfWeek <= 5;
        $isBusinessHour = $date->hour >= 8 && $date->hour < 18;

        return $isWeekday && $isBusinessHour;
    }

    public function render()
    {
        $technicians = User::permission('appointments.be_assigned')->get();

        return view('livewire.appointments.appointment-calendar', [
            'technicians' => $technicians
        ]);
    }
}
