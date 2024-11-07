<?php

namespace App\Http\Controllers;

// use App\Models\Role;
// use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_roles')->only('index');
        $this->middleware('can:create_role')->only('create', 'store');
        $this->middleware('can:edit_role')->only('editPermissions', 'update');
        $this->middleware('can:delete_role')->only('destroy');
    }

    // Method to get default index view roles
    public function index()
    {
        $this->authorize('view_roles');

        // Get all available roles
        $roles = Role::all();

        // Return roles view
        return view('roles.index', compact('roles'));
    }

    // Method to get create roles view
    public function create()
    {
        $this->authorize('create_role');

        // Return create role view
        return view('roles.create');
    }

    // Method to post or insert new role
    public function store(Request $request)
    {
        $this->authorize('create_role');

        // Validate data after insert new role
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:roles,name',
        ]);

        // Execute create on db
        Role::create($validatedData);

        // Return success message on succesfull query
        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    // Method to get view to edit permissions by role
    public function editPermissions($id)
    {
        $this->authorize('edit_role');

        $role = Role::findOrFail($id); // Request for role id
        $permissions = Permission::all(); // Fetch all permissions on db
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit-permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    // Method to update role permissions
    public function update(Request $request, $id)
    {
        $this->authorize('edit_role');

        // Verify is roleId exists
        $role = Role::findOrFail($id);

        // If rol id not found
        if (!$role) {
            // Redirect to index with error message
            return redirect()->back()->with('error', 'Rol no encontrado.');
        }

        // Validate request with permissions array and finding existing permissions id
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Get permissions id from form
        $validated = $request->input('permissions', []);

        if (!$validated || empty($validated)) {
            return redirect()->back()->with('error', 'No puede vaciar la lista de permisos.');
        }

        $permissionNames = Permission::whereIn('id', $validated)->pluck('name');

        // Depuration
        // dd($permissions);

        // Sync permissions with db
        $role->syncPermissions($permissionNames);

        // Return success if permissions has been added successfully
        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente.');
    }

    // Method to destroy or delete role by id
    public function destroy($id)
    {
        $this->authorize('delete_role');

        // Find or fail role by id
        $role = Role::findOrFail($id);

        // Prevent to delete main role 'admin'
        if ($role->name === 'admin') {
            // Redirect to index with error message
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar el rol de administrador.');
        }

        // Find all users with current role id
        $usersWithRole = User::where('rol_id', $role->id)->count();
        // If query has results, do not delete any role or user
        if ($usersWithRole > 0) {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar el rol "' . $role->name . '" porque está asociado a uno o más usuarios.');
        }

        // Execute delete from database
        $role->delete();

        // If operation has succesful, redirect to index with success message
        return redirect()->route('roles.index')->with('success', 'Rol "' . $role->name . '" eliminado correctamente.');
    }
}
