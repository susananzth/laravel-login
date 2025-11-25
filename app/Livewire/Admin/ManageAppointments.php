<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAppointments extends Component
{
    use WithPagination;

    public $showModal = false;
    public $appointmentId = null;

    // Campos del formulario
    public $client_name; // Solo lectura
    public $service_name; // Solo lectura
    public $date;
    public $time;
    public $technician_id;
    public $status;
    public $notes;

    // Reglas de validación
    protected $rules = [
        'date' => 'required|date',
        'time' => 'required',
        'status' => 'required',
        'technician_id' => 'nullable|exists:users,id',
    ];

    public function updatedShowModal($value)
    {
        $this->dispatch('show-appointment-modal-changed', $value);
    }

    public function edit($id)
    {
        $cita = Appointment::with(['client', 'service'])->findOrFail($id);

        $this->appointmentId = $id;
        $this->client_name = $cita->client->firstname . ' ' . $cita->client->lastname;
        $this->service_name = $cita->service->name;
        $this->date = $cita->scheduled_at->format('Y-m-d');
        $this->time = $cita->scheduled_at->format('H:i');
        $this->technician_id = $cita->technician_id;
        $this->status = $cita->status;
        $this->notes = $cita->notes;

        $this->showModal = true;
        $this->dispatch('open-modal', 'appointment-manager-modal');
    }

    public function save()
    {
        $this->validate();

        $cita = Appointment::findOrFail($this->appointmentId);

        $cita->update([
            'scheduled_at' => Carbon::parse($this->date . ' ' . $this->time),
            'technician_id' => $this->technician_id ?: null,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        $this->showModal = false;
        $this->dispatch('close-modal');
        $this->dispatch('notify', 'Cita actualizada correctamente');
    }

    public function delete($id)
    {
        // En lugar de borrar físicamente, un admin suele CANCELAR
        // Pero si quieres borrar: Appointment::find($id)->delete();

        $cita = Appointment::find($id);
        $cita->status = 'cancelled';
        $cita->save();

        // $this->dispatch('notify', 'Cita cancelada');
    }

    public function render()
    {
        $appointments = Appointment::with(['client', 'service', 'technician'])
            ->latest('scheduled_at')
            ->paginate(10);

        $technicians = User::role('technician')->get();

        return view('livewire.admin.manage-appointments', [
            'appointments' => $appointments,
            'technicians' => $technicians
        ]);
    }
}
