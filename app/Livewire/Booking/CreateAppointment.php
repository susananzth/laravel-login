<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

        // Lógica simple: Horas fijas de 9am a 5pm
        // (Aquí puedes agregar lógica compleja para excluir horas ocupadas)
        $slots = [];
        $start = Carbon::parse($this->date . ' 09:00');
        $end = Carbon::parse($this->date . ' 17:00');

        while($start < $end) {
            $slots[] = $start->format('H:i');
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

        Appointment::create([
            'user_id' => Auth::id(),
            'service_id' => $this->service_id,
            'scheduled_at' => Carbon::parse($this->date . ' ' . $this->time),
            'status' => 'pending',
            'notes' => $this->notes
        ]);

        session()->flash('message', '¡Cita agendada con éxito!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.booking.create-appointment');
    }
}
