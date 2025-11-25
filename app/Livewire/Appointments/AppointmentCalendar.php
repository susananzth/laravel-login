<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

        if ($user->hasRole('client')) {
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('technician')) {
            $query->where('technician_id', $user->id);
        }
        // Si es Admin, ve todo (no entra en los if anteriores)

        return $query->get()->map(function ($cita) use ($user) {
            // Colores según estado
            $color = match ($cita->status) {
                'pending' => '#F59E0B', // Ambar
                'confirmed' => '#3B82F6', // Azul
                'completed' => '#10B981', // Verde
                'cancelled' => '#EF4444', // Rojo
                default => '#6B7280',
            };

            $title = $user->hasRole('client')
                ? $cita->service->name
                : $cita->client->name . ' - ' . $cita->service->name;

            return [
                'id' => $cita->id,
                'title' => $title,
                'start' => $cita->scheduled_at->toIso8601String(),
                'color' => $color,
                'extendedProps' => [
                    'status' => $cita->status,
                    'technician' => $cita->technician?->name ?? 'Sin asignar',
                    'notes' => $cita->notes
                ]
            ];
        });
    }

    // Guardar cambios (Asignar técnico o cambiar estado)
    public function updateAppointment()
    {
        // Solo admin puede hacer esto (seguridad extra)
        if (!Auth::user()->hasRole('admin')) abort(403);

        $this->validate([
            'status' => 'required',
            // Technician puede ser null si se desasigna
        ]);

        $this->selectedAppointment->update([
            'technician_id' => $this->technician_id ?: null, // Si viene vacío, null
            'status' => $this->status,
            'notes' => $this->adminNotes
        ]);

        $this->showModal = false;

        // Emitimos evento para refrescar calendario y notificar
        $this->dispatch('refresh-calendar');
        $this->dispatch('notify', 'Cita actualizada correctamente.');
    }

    // Cargar datos al hacer clic en el calendario
    public function editAppointment($id)
    {
        $this->selectedAppointment = Appointment::findOrFail($id);
        $this->technician_id = $this->selectedAppointment->technician_id;
        $this->status = $this->selectedAppointment->status;
        $this->adminNotes = $this->selectedAppointment->notes;

        $this->showModal = true; // Abrimos modal

        // Emitimos el evento de Alpine para abrir el modal. El nombre debe coincidir con el 'name' del x-modal.
        $this->dispatch('open-modal', 'admin-appointment-manager');
    }

    // Método para mover citas (Drag & Drop) - Solo Admin y Cliente (con reglas)
    public function updateAppointmentDate($id, $newDate)
    {
        $cita = Appointment::findOrFail($id);
        $user = Auth::user();

        // Validaciones rápidas
        if ($user->hasRole('client') && $cita->user_id !== $user->id) abort(403);
        if ($user->hasRole('technician')) return; // Técnicos no mueven citas

        // El cliente solo puede mover si falta más de 24hrs (Regla de negocio común)
        if ($user->hasRole('client') && $cita->scheduled_at->diffInHours(now()) < 24) {
            $this->dispatch('notify', 'No se puede reprogramar con menos de 24h de antelación.');
            return;
        }

        $cita->update(['scheduled_at' => Carbon::parse($newDate)]);
        $this->dispatch('notify', 'Cita reprogramada con éxito.');
    }

    public function render()
    {
        // Obtener solo usuarios con rol 'technician'
        $technicians = User::role('technician')->get();

        return view('livewire.appointments.appointment-calendar', [
            'technicians' => $technicians
        ]);
    }
}
