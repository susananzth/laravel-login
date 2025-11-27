<?php

namespace App\Livewire\Booking;

use App\Mail\AppointmentNotification;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateAppointment extends Component
{
    public $step = 1; // Control de pasos (Wizard simple)

    // Form Data
    public $service_id;
    public $date; // Y-m-d
    public $time; // H:i
    public $notes;

    public function mount() {
        $this->date = Carbon::tomorrow()->format('Y-m-d');
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
        $this->validate([
            'service_id' => 'required',
            'date' => 'required|date|after:today',
            'time' => 'required',
        ]);

        $cita = Appointment::create([
            'user_id' => Auth::id(),
            'service_id' => $this->service_id,
            'scheduled_at' => Carbon::parse($this->date . ' ' . $this->time),
            'status' => 'pending',
            'notes' => $this->notes
        ]);

        Mail::to(Auth::user()->email)->send(new AppointmentNotification($cita, 'created'));

        session()->flash('message', '¡Cita agendada con éxito!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.booking.create-appointment');
    }
}
