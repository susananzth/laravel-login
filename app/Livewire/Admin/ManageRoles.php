<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManageRoles extends Component
{
    use WithPagination;

    public $name;
    public $selectedPermissions = []; // Array de IDs de permisos seleccionados
    public $roleId = null;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|unique:roles,name',
        'selectedPermissions' => 'array'
    ];

    public function updatedShowModal($value)
    {
        $this->dispatch('show-role-modal-changed', $value);
    }

    public function create()
    {
        $this->reset(['name', 'roleId', 'selectedPermissions']);
        $this->resetErrorBag();
        $this->showModal = true;
        $this->dispatch('open-modal', 'role-manager-modal');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        // Cargar permisos actuales del rol (solo IDs)
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->resetErrorBag();
        $this->showModal = true;
        $this->dispatch('open-modal', 'role-manager-modal');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array'
        ]);

        $role = Role::updateOrCreate(['id' => $this->roleId], ['name' => $this->name]);

        // Sincronizar permisos (usando nombres de permisos)
        $role->syncPermissions($this->selectedPermissions);

        $this->showModal = false;
        $this->dispatch('close-modal');
        $this->dispatch('notify', 'Rol guardado correctamente.');
    }

    public function delete($id)
    {
        // Proteger roles críticos
        if (in_array(Role::find($id)->name, ['admin', 'client', 'technician'])) {
            $this->dispatch('error', 'No puedes eliminar roles del sistema.');
            return;
        }

        Role::find($id)->delete();
        $this->dispatch('notify', 'Rol eliminado.');
    }

    public function render()
    {
        return view('livewire.admin.manage-roles', [
            'roles' => Role::with('permissions')->paginate(10),
            'permissions' => Permission::all()->groupBy(function($data) {
                // Agrupar permisos por 'recurso' (ej: users, appointments)
                return explode('.', $data->name)[0];
            })
        ]);
    }
}
