<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ManageServices extends Component
{
    use WithPagination;

    // Propiedades del Servicio
    public $name, $description, $price, $duration_minutes, $is_active = true;
    public $serviceId = null;
    public $showModal = false;

    // Reglas de validación
    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:services,name,' . $this->serviceId,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15',
            'is_active' => 'boolean',
        ];
    }

    public function updatedShowModal($value)
    {
        $this->dispatch('show-service-modal-changed', $value);
    }

    public function create()
    {
        abort_unless(auth()->user()->hasPermissionTo('services.create'), 403);

        $this->resetInputFields();
        $this->showModal = true;
        $this->dispatch('open-modal', 'service-manager-modal');
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->hasAnyPermission(['services.view', 'services.edit']), 403);

        $service = Service::findOrFail($id);
        $this->serviceId = $id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->price = $service->price;
        $this->duration_minutes = $service->duration_minutes;
        $this->is_active = (bool) $service->is_active;

        $this->resetErrorBag();
        $this->showModal = true;
        $this->dispatch('open-modal', 'service-manager-modal');
    }

    public function save()
    {
        abort_unless(auth()->user()->hasAnyPermission(['services.create', 'services.edit']), 403);

        $this->validate($this->rules());

        Service::updateOrCreate(['id' => $this->serviceId], [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration_minutes' => $this->duration_minutes,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        $this->dispatch('close-modal');
        $this->resetInputFields();
        $this->dispatch('notify', 'Servicio guardado correctamente.');
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->hasPermissionTo('services.delete'), 403);

        if (Service::find($id)->appointments()->exists()) {
            $this->dispatch('app-error', 'No puedes eliminar porque tiene citas asociadas.');
            return;
        }

        if ($id) {
            Service::find($id)->delete();
            $this->dispatch('notify', 'Servicio eliminado.');
        }
    }

    private function resetInputFields()
    {
        $this->reset(['name', 'description', 'price', 'duration_minutes', 'is_active', 'serviceId']);
        $this->resetErrorBag();
    }

    public function render()
    {
        abort_unless(auth()->user()->hasPermissionTo('services.view'), 403);

        return view('livewire.admin.manage-services', [
            'services' => Service::latest()->paginate(10),
        ]);
    }
}
