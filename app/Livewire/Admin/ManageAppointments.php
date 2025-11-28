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

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . now()->addMonths(3)->format('Y-m-d')
            ],
            'time' => [
                'required',
                'string',
                'regex:/^(0[8-9]|1[0-7]):[0-5][0-9]$/',
                function ($attribute, $value, $fail) {
                    if ($this->date && $value) {
                        $selectedDateTime = Carbon::parse($this->date . ' ' . $value);
                        
                        if ($selectedDateTime->lt(now())) {
                            $fail('No se puede programar en el pasado.');
                            return;
                        }
                        
                        if (!$this->isBusinessHours($selectedDateTime)) {
                            $fail('Horario no laboral (L-V 8:00-18:00).');
                            return;
                        }
                    }
                }
            ],
            'technician_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereHas('permissions', function($q) {
                        $q->where('name', 'appointments.be_assigned');
                    });
                })
            ],
            'status' => [
                'required',
                'string',
                'in:pending,confirmed,in_progress,completed,cancelled',
                'max:20'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if ($value && preg_match('/<script|javascript:|onclick|onload|onerror/i', $value)) {
                        $fail('Contenido no permitido en las notas.');
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'date.after_or_equal' => 'La fecha no puede ser en el pasado.',
            'date.before_or_equal' => 'No puede programar con más de 3 meses de anticipación.',
            'time.regex' => 'Formato de hora inválido (08:00 - 17:59).',
            'technician_id.exists' => 'El técnico seleccionado no es válido.',
            'status.in' => 'Estado de cita no válido.',
            'notes.max' => 'Las notas no pueden exceder 500 caracteres.',
        ];
    }

    public function updatedShowModal($value)
    {
        $this->dispatch('show-appointment-modal-changed', $value);
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->hasPermissionTo('appointments.edit'), 403);

        $appointment = Appointment::with(['client', 'service'])->findOrFail($id);

        $this->appointmentId = $id;
        $this->client_name = $appointment->client->firstname . ' ' . $appointment->client->lastname;
        $this->service_name = $appointment->service->name;
        $this->date = $appointment->scheduled_at->format('Y-m-d');
        $this->time = $appointment->scheduled_at->format('H:i');
        $this->technician_id = $appointment->technician_id;
        $this->status = $appointment->status;
        $this->notes = $appointment->notes;

        $this->showModal = true;
        $this->dispatch('open-modal', 'appointment-manager-modal');
    }

    public function save()
    {
        abort_unless(auth()->user()->hasPermissionTo('appointments.edit'), 403);

        $this->validate();

        try {
            $appointment = Appointment::findOrFail($this->appointmentId);
            // Validar fecha y hora combinadas
            $scheduledDateTime = Carbon::parse($this->date . ' ' . $this->time);
            
            if ($scheduledDateTime->lt(now())) {
                $this->addError('time', 'La fecha y hora deben ser iguales o posteriores a la actual.');
                return;
            }
    
            // 1. Llenamos los datos PERO NO GUARDAMOS AÚN
            $appointment->fill([
                'scheduled_at' => $scheduledDateTime,
                'technician_id' => $this->technician_id ?: null,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);
    
            // 2. Detectamos si cambió algo importante para el cliente
            $shouldNotify = $appointment->isDirty(['scheduled_at', 'status']);
    
            // Detectamos si es cancelación para cambiar el asunto del correo
            $type = ($this->status === 'cancelled') ? 'cancelled' : 'updated';
    
            // 3. Guardamos
            $appointment->save();
    
            // 4. Enviamos correo SOLO si hubo cambios relevantes
            if ($shouldNotify) {
                Mail::to($appointment->client->email)->send(new AppointmentNotification($appointment, $type));
            }
    
            $this->showModal = false;
            $this->dispatch('close-modal');
            // Mensaje diferente si se notificó
            $msg = $shouldNotify
                ? 'Cita actualizada y cliente notificado por correo.'
                : 'Cita actualizada correctamente.';
    
            $this->dispatch('notify', $msg);
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error al actualizar la cita.');
        }

    }

    public function delete($id)
    {
        abort_unless(auth()->user()->hasPermissionTo('appointments.cancel'), 403);

        try {
            $appointment = Appointment::findOrFail($id);

            // Validar que no esté completada
            if ($appointment->status === 'completed') {
                $this->dispatch('error', 'No se puede cancelar una cita completada.');
                return;
            }

            $appointment->status = 'cancelled';
            $appointment->save();

            Mail::to($appointment->client->email)->send(new AppointmentNotification($appointment, 'cancelled'));

            $this->dispatch('notify', 'Cita cancelada y notificada por correo.');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error al cancelar la cita.');
        }
    }

    private function isBusinessHours(Carbon $dateTime)
    {
        $isWeekday = $dateTime->dayOfWeek >= 1 && $dateTime->dayOfWeek <= 5;
        $isBusinessHour = $dateTime->hour >= 8 && $dateTime->hour < 18;
        return $isWeekday && $isBusinessHour;
    }

    public function render()
    {
        abort_unless(auth()->user()->hasAnyPermission(['appointments.view_all', 'appointments.view_own']), 403);

        $query = Appointment::with(['client', 'service', 'technician'])
            ->latest('scheduled_at');

        // Lógica de permisos para filtrar citas
        if (auth()->user()->hasPermissionTo('appointments.view_all')) {
            // Ver todas
        } elseif (auth()->user()->hasPermissionTo('appointments.be_assigned')) {
            $query->where('technician_id', auth()->id());
        } else {
            $query->where('user_id', auth()->id());
        }

        $appointments = $query->paginate(10);

        $technicians = User::permission('appointments.be_assigned')->get();

        return view('livewire.admin.manage-appointments', [
            'appointments' => $appointments,
            'technicians' => $technicians
        ]);
    }
}
