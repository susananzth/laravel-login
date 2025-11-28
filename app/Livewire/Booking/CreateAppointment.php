<?php

namespace App\Livewire\Booking;

use App\Mail\AppointmentNotification;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateAppointment extends Component
{
    public $step = 1; // Control de pasos (Wizard simple)

    // Form Data
    public $service_id;
    public $date; // Y-m-d
    public $time; // H:i
    public $notes;
    public $availableSlots = [];

    public function rules()
    {
        return [
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where('is_active', true)
            ],
            'date' => [
                'required',
                'date',
                'after_or_equal:' . now()->addDay()->format('Y-m-d'),
                'before_or_equal:' . now()->addMonths(3)->format('Y-m-d')
            ],
            'time' => [
                'required',
                'string',
                'regex:/^(0[8-9]|1[0-7]):[0-5][0-9]$/', // 08:00 - 17:59
                function ($attribute, $value, $fail) {
                    if ($this->date && $value) {
                        $selectedDateTime = Carbon::parse($this->date . ' ' . $value);
                        
                        // Validar que no sea en el pasado
                        if ($selectedDateTime->lt(now())) {
                            $fail('La fecha y hora seleccionadas no pueden ser en el pasado.');
                            return;
                        }
                        
                        // Validar horario laboral (L-V, 8am-6pm)
                        if (!$this->isBusinessHours($selectedDateTime)) {
                            $fail('El horario debe ser entre Lunes a Viernes de 8:00 AM a 6:00 PM.');
                            return;
                        }
                        
                        // Validar disponibilidad
                        if (!$this->isTimeSlotAvailable($selectedDateTime)) {
                            $fail('Este horario no está disponible. Por favor seleccione otro.');
                        }
                    }
                }
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    // Validar caracteres peligrosos
                    if (preg_match('/<script|javascript:|onclick|onload|onerror/i', $value)) {
                        $fail('Las notas contienen contenido no permitido.');
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'service_id.required' => 'Debe seleccionar un servicio.',
            'service_id.exists' => 'El servicio seleccionado no es válido.',
            'date.required' => 'La fecha es obligatoria.',
            'date.after_or_equal' => 'La fecha debe ser a partir de mañana.',
            'date.before_or_equal' => 'No puede agendar con más de 3 meses de anticipación.',
            'time.required' => 'Debe seleccionar una hora.',
            'time.regex' => 'El formato de hora no es válido.',
            'notes.max' => 'Las notas no pueden exceder los 500 caracteres.',
        ];
    }

    public function getServicesProperty() {
        return Service::where('is_active', true)->get();
    }

    public function getAvailableSlotsProperty() {
        if(!$this->service_id || !$this->date) return [];

        // 1. Obtener las horas YA ocupadas para ese día
        // Ignoramos las canceladas para liberar el hueco
        $bookedTimes = Appointment::whereDate('scheduled_at', $this->date)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function($appointment) {
                return $appointment->scheduled_at->format('H:i');
            })->toArray();

        $slots = [];
        $start = Carbon::parse($this->date . ' 09:00');
        $end = Carbon::parse($this->date . ' 17:00');

        while($start < $end) {
            $timeString = $start->format('H:i');

            // 2. Solo agregamos el slot si NO está en la lista de ocupados
            if (!in_array($timeString, $bookedTimes)) {
                $slots[] = $timeString;
            }

            $start->addHour(); // Intervalos de 1 hora por simplicidad
        }
        return $slots;
    }

    public function save() {
        $this->validate();

        $scheduledAt = Carbon::parse($this->date . ' ' . $this->time);
            
        // Validación final de integridad
        if ($scheduledAt->lt(now())) {
            $this->addError('time', 'No se puede agendar en el pasado.');
            return;
        }

        $cita = Appointment::create([
            'user_id' => Auth::id(),
            'service_id' => $this->service_id,
            'scheduled_at' => $scheduledAt,
            'notes' => clean($this->notes), // Limpiar HTML/scripts
            'status' => 'pending'
        ]);

        Mail::to(Auth::user()->email)->send(new AppointmentNotification($cita, 'created'));

        $this->reset(['service_id', 'date', 'time', 'notes', 'availableSlots']);
        $this->dispatch('notify', '¡Cita agendada con éxito!');
        session()->flash('message', '¡Cita agendada con éxito!');
        return redirect()->route('dashboard');
    }

    private function isBusinessHours(Carbon $dateTime)
    {
        // Lunes = 1, Viernes = 5
        $isWeekday = $dateTime->dayOfWeek >= 1 && $dateTime->dayOfWeek <= 5;
        $isBusinessHour = $dateTime->hour >= 8 && $dateTime->hour < 18;
        
        return $isWeekday && $isBusinessHour;
    }

    private function isTimeSlotAvailable(Carbon $dateTime)
    {
        return !Appointment::where('scheduled_at', $dateTime->format('Y-m-d H:i:s'))
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->exists();
    }

    private function getAvailableSlots($date)
    {
        if (!$date) return [];
        
        $slots = [];
        $startHour = 8;
        $endHour = 17;
        
        for ($hour = $startHour; $hour <= $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $timeString = sprintf('%02d:%02d', $hour, $minute);
                $dateTime = Carbon::parse($date . ' ' . $timeString);
                
                if ($this->isBusinessHours($dateTime) && $this->isTimeSlotAvailable($dateTime)) {
                    $slots[] = $timeString;
                }
            }
        }
        
        return $slots;
    }

    public function render()
    {
        return view('livewire.booking.create-appointment');
    }
}
