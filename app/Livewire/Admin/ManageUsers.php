<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ManageUsers extends Component
{
    use WithPagination;

    public $firstname, $lastname, $username, $phone, $email, $password;
    public $roles = [];
    public $userId = null;
    public $showModal = false;

    // Reglas de validación dinámicas
    protected function rules()
    {
        $rules = [
            'firstname' => 'required|min:2',
            'lastname'  => 'required|min:2',
            'username'  => 'required|unique:users,username,' . $this->userId,
            'phone'     => 'required',
            'email'     => 'required|email|unique:users,email,' . $this->userId,
            'roles'     => 'array',
        ];

        // Solo requerir password si es usuario nuevo
        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        return $rules;
    }

    // Método Hook para sincronizar con Alpine JS
    public function updatedShowModal($value)
    {
        $this->dispatch('show-modal-changed', $value);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->showModal = true;
        $this->dispatch('open-modal', 'user-manager-modal');
    }

    public function save() {
        $this->validate($this->rules());

        $data = [
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'username'  => $this->username,
            'phone'     => $this->phone,
            'email'     => $this->email,
        ];

        // Solo encriptamos y guardamos password si el campo tiene valor
        if (!empty($this->password)) {
            $data['password'] = bcrypt($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);

        // Sincronizar rol usando Spatie
        $user->syncRoles($this->roles);

        $this->showModal = false; // El hook updatedShowModal cerrará el modal visualmente

        // Notificación opcional (si tienes un componente de notificaciones)
        // $this->dispatch('notify', $this->userId ? 'Usuario actualizado' : 'Usuario creado');

        // Volvemos a disparar el evento de cerrar para Alpine
        $this->dispatch('close-modal');

        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->firstname = $user->firstname;
        $this->lastname  = $user->lastname;
        $this->username  = $user->username;
        $this->phone     = $user->phone;
        $this->email     = $user->email;
        $this->roles     = $user->roles->pluck('name')->toArray();

        $this->password = ''; // Limpiamos password para no mostrar el hash

        $this->showModal = true;
        // Importante: Disparar evento para abrir modal
        $this->dispatch('open-modal', 'user-manager-modal');
    }

    public function delete($id)
    {
        if($id) {
            // Evitar auto-eliminación
            if($id === auth()->id()) {
                $this->dispatch('error', 'No puedes eliminar tu propia cuenta');
                return;
            }
            User::find($id)->delete();
            // FALTA: Emitir notificación
        }
    }

    private function resetInputFields()
    {
        $this->reset(['firstname', 'lastname', 'username', 'phone', 'email', 'password', 'roles', 'userId']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.manage-users', [
            'users' => User::with('roles')->latest()->paginate(10),
            'availableRoles' => Role::all(),
        ]);
    }
}
