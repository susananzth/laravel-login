<?php

namespace App\Livewire\Admin;

use App\Mail\AppointmentNotification;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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

        // 1. Llenamos los datos PERO NO GUARDAMOS AÚN
        $cita->fill([
            'scheduled_at' => Carbon::parse($this->date . ' ' . $this->time),
            'technician_id' => $this->technician_id ?: null,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        // 2. Detectamos si cambió algo importante para el cliente
        $shouldNotify = $cita->isDirty(['scheduled_at', 'status']);

        // Detectamos si es cancelación para cambiar el asunto del correo
        $type = ($this->status === 'cancelled') ? 'cancelled' : 'updated';

        // 3. Guardamos
        $cita->save();

        // 4. Enviamos correo SOLO si hubo cambios relevantes
        if ($shouldNotify) {
            Mail::to($cita->client->email)->send(new AppointmentNotification($cita, $type));
        }

        $this->showModal = false;
        $this->dispatch('close-modal');
        // Mensaje diferente si se notificó
        $msg = $shouldNotify
            ? 'Cita actualizada y cliente notificado por correo.'
            : 'Cita actualizada correctamente.';

        $this->dispatch('notify', $msg);
    }

    public function delete($id)
    {
        $cita = Appointment::find($id);
        $cita->status = 'cancelled';
        $cita->save();

        Mail::to($cita->client->email)->send(new AppointmentNotification($cita, 'cancelled'));

        $this->dispatch('notify', 'Cita cancelada y cliente notificado por correo.');
    }

    public function render()
    {
        $appointments = Appointment::with(['client', 'service', 'technician'])
            ->latest('scheduled_at')
            ->paginate(10);

        $technicians = User::permission('appointments.be_assigned')->get();

        return view('livewire.admin.manage-appointments', [
            'appointments' => $appointments,
            'technicians' => $technicians
        ]);
    }
}
