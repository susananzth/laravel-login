<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Reports extends Component
{
    // Filtros
    public $range = 'month'; // month, year, all
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Por defecto: Este mes
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedRange($value)
    {
        switch ($value) {
            case 'month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'last_3_months':
                $this->startDate = Carbon::now()->subMonths(3)->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
        }

        // Disparamos evento para que el JS actualice los gráficos
        $this->dispatch('update-charts');
    }

    public function render()
    {
        abort_unless(auth()->user()->hasPermissionTo('reports.view'), 403);

        // 1. Ingresos por Mes (Line Chart)
        // Agrupamos por fecha
        $incomeData = Appointment::where('status', 'completed')
            ->whereBetween('scheduled_at', [$this->startDate, $this->endDate])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->selectRaw('DATE(scheduled_at) as date, sum(services.price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Servicios Más Solicitados (Doughnut Chart)
        $popularServices = Appointment::whereBetween('scheduled_at', [$this->startDate, $this->endDate])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as total'))
            ->groupBy('services.name')
            ->get();

        // 3. Citas por Estado (Bar Chart)
        $statusCounts = Appointment::whereBetween('scheduled_at', [$this->startDate, $this->endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return view('livewire.admin.reports', [
            'incomeLabels' => $incomeData->pluck('date'),
            'incomeValues' => $incomeData->pluck('total'),
            'serviceLabels' => $popularServices->pluck('name'),
            'serviceValues' => $popularServices->pluck('total'),
            'statusData' => $statusCounts,
        ]);
    }
}
