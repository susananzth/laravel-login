<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

/**
 * Componente Livewire para la gestión CRUD (Create, Read, Update, Delete) de usuarios.
 * Maneja la lógica de negocio, validación y comunicación con la base de datos.
 */
class ManageUsers extends Component
{
    // Trait para manejar la paginación de resultados sin recargar la página completa.
    use WithPagination;

    // Propiedades públicas: Están sincronizadas automáticamente con el formulario en la vista (Two-way binding).
    public $firstname, $lastname, $username, $phone, $email, $password;

    // Almacena los roles seleccionados (array de strings).
    public $roles = [];

    // ID del usuario en edición. Si es null, estamos creando uno nuevo.
    public $userId = null;

    // Controla la visibilidad del modal desde el lado del servidor.
    public $showModal = false;

    /**
     * Define las reglas de validación dinámicamente.
     * * @return array Reglas de validación de Laravel.
     */
    protected function rules()
    {
        $rules = [
            'firstname' => 'required|min:2',
            'lastname'  => 'required|min:2',
            // 'unique:users...': Verifica que no exista otro usuario con este username, EXCEPTO el usuario actual ($this->userId).
            'username'  => 'required|unique:users,username,' . $this->userId,
            'phone'     => 'required',
            'email'     => 'required|email|unique:users,email,' . $this->userId,
            'roles'     => 'array',
        ];

        // Lógica condicional:
        // Si no hay ID (Crear), el password es OBLIGATORIO.
        // Si hay ID (Editar), el password es OPCIONAL (nullable), solo se valida si el usuario escribe algo.
        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        return $rules;
    }

    /**
     * Hook de ciclo de vida de Livewire.
     * Se ejecuta automáticamente cuando la propiedad $showModal cambia.
     * Sirve para sincronizar el estado del modal con AlpineJS en el frontend.
     */
    public function updatedShowModal($value)
    {
        $this->dispatch('show-modal-changed', $value);
    }

    /**
     * Prepara el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // SEGURIDAD: Verifica si el usuario autenticado tiene permiso explícito.
        // Si no, lanza un error 403 (Prohibido).
        abort_unless(auth()->user()->hasPermissionTo('users.create'), 403);

        $this->resetInputFields();
        $this->showModal = true;

        // Dispara evento al navegador para que AlpineJS abra el modal visualmente.
        $this->dispatch('open-modal', 'user-manager-modal');
    }

    /**
     * Guarda o Actualiza un usuario en la base de datos.
     */
    public function save()
    {
        // SEGURIDAD: Debe tener permiso de crear O editar.
        abort_unless(auth()->user()->hasAnyPermission(['users.create', 'users.edit']), 403);

        // 1. Ejecuta las validaciones definidas en rules(). Si falla, se detiene y muestra errores.
        $this->validate($this->rules());

        $data = [
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'username'  => $this->username,
            'phone'     => $this->phone,
            'email'     => $this->email,
        ];

        // 2. Encriptado de contraseña: Solo si el campo no está vacío.
        // NUNCA guardar contraseñas en texto plano. 'bcrypt' es el algoritmo estándar.
        if (!empty($this->password)) {
            $data['password'] = bcrypt($this->password);
        }

        // 3. UpdateOrCreate: Busca por ID. Si existe, actualiza. Si no (null), crea uno nuevo.
        // Es un patrón eficiente para no duplicar lógica de create/update.
        $user = User::updateOrCreate(['id' => $this->userId], $data);

        // 4. Asignación de Roles (Paquete Spatie).
        // Sincroniza los roles seleccionados con la tabla intermedia model_has_roles.
        $user->syncRoles($this->roles);

        // 5. Limpieza y cierre de UI.
        $this->showModal = false;
        $this->dispatch('close-modal');
        $this->resetInputFields();
    }

    /**
     * Carga los datos de un usuario existente para editarlo.
     * @param int $id ID del usuario a editar.
     */
    public function edit($id)
    {
        abort_unless(auth()->user()->hasAnyPermission(['users.view', 'users.edit']), 403);

        $user = User::findOrFail($id); // Si no encuentra el ID, lanza error 404.

        // Rellenamos las propiedades públicas con los datos de la BD.
        $this->userId = $id;
        $this->firstname = $user->firstname;
        $this->lastname  = $user->lastname;
        $this->username  = $user->username;
        $this->phone     = $user->phone;
        $this->email     = $user->email;

        // Extraemos solo los nombres de los roles para el checkbox.
        $this->roles     = $user->roles->pluck('name')->toArray();

        $this->password = ''; // Limpiamos password por seguridad (no se debe mostrar el hash).

        $this->showModal = true;
        $this->dispatch('open-modal', 'user-manager-modal');
    }

    /**
     * Elimina un usuario (Soft delete o hard delete según configuración del modelo).
     */
    public function delete($id)
    {
        abort_unless(auth()->user()->hasPermissionTo('users.delete'), 403);

        if($id) {
            // Validación lógica de negocio: Evitar suicidio digital (borrarse a uno mismo).
            if($id === auth()->id()) {
                $this->dispatch('app-error', 'No puedes eliminar tu propia cuenta');
                return;
            }
            User::find($id)->delete();
        }
    }

    /**
     * Helper para limpiar todas las variables del formulario.
     */
    private function resetInputFields()
    {
        $this->reset(['firstname', 'lastname', 'username', 'phone', 'email', 'password', 'roles', 'userId']);
        $this->resetErrorBag(); // Limpia los mensajes de error rojos.
    }

    /**
     * Renderiza la vista Blade asociada.
     */
    public function render()
    {
        abort_unless(auth()->user()->hasPermissionTo('users.view'), 403);

        return view('livewire.admin.manage-users', [
            // Pasamos los usuarios paginados y cargamos la relación 'roles' (Eager Loading)
            // para evitar el problema N+1 queries (optimización de base de datos).
            'users' => User::with('roles')->latest()->paginate(10),
            'availableRoles' => Role::all(),
        ]);
    }
}
