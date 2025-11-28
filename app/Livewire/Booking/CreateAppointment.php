<?php

namespace App\Livewire\Booking;

use App\Mail\AppointmentNotification;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class CreateAppointment extends Component
{
    public $step = 1; // Control de pasos (Wizard simple)

    // Form Data
    public $service_id;
    public $date; // Y-m-d
    public $time; // H:i
    public $notes;

    // Computed property para los slots disponibles
    #[Computed]
    public function availableSlots()
    {
        if (!$this->service_id || !$this->date) return [];

        $selectedDate = Carbon::parse($this->date);
        $now = now();

        // 1. Validaciones básicas de fecha (Pasado, Futuro, Fines de semana)
        if ($selectedDate->lt($now->startOfDay())) return [];
        if ($selectedDate->gt($now->copy()->addMonths(3))) return [];
        if ($selectedDate->isWeekend()) return [];

        // 2. Obtener citas ocupadas
        $bookedTimes = Appointment::whereDate('scheduled_at', $this->date)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->pluck('scheduled_at') // Más eficiente que get() + map()
            ->map(fn($date) => $date->format('H:i'))
            ->toArray();

        $slots = [];
        $start = Carbon::parse($this->date . ' 08:00');
        $end = Carbon::parse($this->date . ' 17:30');

        while ($start <= $end) {
            // Lógica CRÍTICA añadida:
            // Si la fecha seleccionada es HOY, no mostrar horas que ya pasaron.
            if ($selectedDate->isToday() && $start->lt($now)) {
                $start->addMinutes(30);
                continue;
            }

            $timeString = $start->format('H:i');
            
            if (!in_array($timeString, $bookedTimes)) {
                $slots[] = $timeString;
            }
            
            $start->addMinutes(30);
        }
        
        return $slots;
    }

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
                'before_or_equal:' . now()->addMonths(3)->format('Y-m-d'),
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $date = Carbon::parse($value);
                        // Validar que sea día laboral
                        if ($date->dayOfWeek < 1 || $date->dayOfWeek > 5) {
                            $fail('Solo puede agendar citas de Lunes a Viernes.');
                        }
                    }
                }
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
                        
                        // Validar que el slot esté disponible
                        $availableSlots = $this->availableSlots;
                        if (!in_array($value, $availableSlots)) {
                            $fail('Este horario ya no está disponible. Por favor seleccione otro.');
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
                    if ($value && preg_match('/<script|javascript:|onclick|onload|onerror/i', $value)) {
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
            'time.regex' => 'El formato de hora no es válido. Use formato HH:MM entre 08:00 y 17:59.',
            'notes.max' => 'Las notas no pueden exceder los 500 caracteres.',
        ];
    }

    public function updated($propertyName)
    {
        // Validar individualmente cada campo cuando cambia
        if (in_array($propertyName, ['service_id', 'date', 'time', 'notes'])) {
            $this->validateOnly($propertyName);
        }

        // Cuando cambia la fecha o servicio, actualizar slots disponibles
        if (in_array($propertyName, ['service_id', 'date'])) {
            $this->reset('time');
        }
    }

    public function updatedDate($value)
    {
        $this->reset('time');
        $this->validateOnly('date');
    }

    public function updatedServiceId($value)
    {
        $this->validateOnly('service_id');
    }

    public function getServicesProperty() 
    {
        return Service::where('is_active', true)->get();
    }

    public function save() 
    {
        $this->validate();

        try {
            $scheduledAt = Carbon::parse($this->date . ' ' . $this->time);
            
            // Validación final de integridad
            if ($scheduledAt->lt(now())) {
                $this->addError('time', 'No se puede agendar en el pasado.');
                return;
            }

            // Verificar disponibilidad final (por si alguien más reservó)
            $exists = Appointment::where('scheduled_at', $scheduledAt)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                $this->addError('time', 'El horario fue tomado hace un instante.');
                return;
            }

            $cita = Appointment::create([
                'user_id' => Auth::id(),
                'service_id' => $this->service_id,
                'scheduled_at' => $scheduledAt,
                'notes' => $this->cleanNotes($this->notes),
                'status' => 'pending'
            ]);

            // Enviar email de confirmación
            Mail::to(Auth::user()->email)->send(new AppointmentNotification($cita, 'created'));

            // Reset y redirección
            $this->reset(['service_id', 'date', 'time', 'notes']);

            $this->dispatch('notify', '¡Cita agendada con éxito!');

            return redirect()->route('dashboard')->with('success', '¡Cita agendada con éxito!');

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error al agendar la cita. Por favor intente nuevamente.');
        }
    }

    /**
     * Limpia y sanitiza las notas
     */
    private function cleanNotes($notes)
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

    /**
     * Verifica si una fecha y hora están en horario laboral
     */
    private function isBusinessHours(Carbon $dateTime)
    {
        $isWeekday = $dateTime->dayOfWeek >= 1 && $dateTime->dayOfWeek <= 5;
        $isBusinessHour = $dateTime->hour >= 8 && $dateTime->hour < 18;
        
        return $isWeekday && $isBusinessHour;
    }

    public function render()
    {
        return view('livewire.booking.create-appointment', [
            'availableSlots' => $this->availableSlots
        ]);
    }
}