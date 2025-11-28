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
    public $selectedPermissions = []; // Array de nombres de permisos seleccionados
    public $roleId = null;
    public $isSystemRole = false;
    public $showModal = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:50|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array'
        ];

        return $rules;
    }

    public function updatedShowModal($value)
    {
        $this->dispatch('show-role-modal-changed', $value);
    }

    public function create()
    {
        abort_unless(auth()->user()->hasPermissionTo('roles.create'), 403);

        $this->reset(['name', 'roleId', 'selectedPermissions']);
        $this->resetErrorBag();
        $this->showModal = true;
        $this->dispatch('open-modal', 'role-manager-modal');
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->hasAnyPermission(['roles.view', 'roles.edit']), 403);

        $role = Role::findOrFail($id);

        $this->isSystemRole = in_array($role->id, [1, 2, 3]);

        $this->roleId = $id;
        $this->name = $role->name;
        // Cargar permisos actuales del rol
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->resetErrorBag();
        $this->showModal = true;
        $this->dispatch('open-modal', 'role-manager-modal');
    }

    public function save()
    {
        abort_unless(auth()->user()->hasAnyPermission(['roles.create', 'roles.edit']), 403);

        if ($this->isSystemRole) {
            $this->dispatch('app-error', 'Este es un rol del sistema, no se puede editar.');
            return;
        }

        $this->validate($this->rules());

        if ($this->roleId) {
            $role = Role::find($this->roleId);
            $role->name = $this->name;
            $role->save();
        } else {
            // Crear nuevo rol
            $role = Role::create(['name' => $this->name]);
        }

        // Sincronizar permisos (usando nombres de permisos)
        $role->syncPermissions($this->selectedPermissions);

        $this->showModal = false;
        $this->dispatch('close-modal');
        $this->dispatch('notify', 'Rol guardado correctamente.');
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->hasPermissionTo('roles.delete'), 403);

        $role = Role::find($id);

        // Proteger roles críticos
        if (in_array($role->id, [1, 2, 3])) {
            $this->dispatch('app-error', 'No puedes eliminar roles del sistema.');
            return;
        }

        $role->delete();
        $this->dispatch('notify', 'Rol eliminado.');
    }

    public function render()
    {
        abort_unless(auth()->user()->hasPermissionTo('roles.view'), 403);

        return view('livewire.admin.manage-roles', [
            'roles' => Role::with('permissions')->paginate(10),
            'permissions' => Permission::all()->groupBy(function($data) {
                return explode('.', $data->name)[0];
            })
        ]);
    }
}
