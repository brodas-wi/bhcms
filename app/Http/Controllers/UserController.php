<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Models\Role;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_users')->only('index');
        $this->middleware('can:create_user')->only('create', 'store');
        $this->middleware('can:edit_user')->only('edit', 'update');
        $this->middleware('can:delete_user')->only('destroy');
    }

    // Method to get default users view
    public function index(Request $request)
    {
        $this->authorize('view_users');

        // Get all users with roles
        // $users = User::with('roles')->get();
        // $users = User::with('roles')->paginate(10);

        // Get value from 'perPage' from request or default value 5
        $perPage = $request->input('perPage', 5);

        // Paginate users with selected quantity
        $users = User::with('roles')->paginate($perPage);

        // Return user view with results
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create_user');

        // Get all available roles
        $roles = Role::all();
        // Return edit user view
        return view('users.create', compact('roles'));
    }

    // Method to create or post nuew user information
    public function store(Request $request)
    {
        // dd($request);
        // Vlaidate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // dd($request);

        // Create request for new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asign role id if exists
        if ($request->filled('rol_id')) {
            $role = Role::find($request->rol_id);
            $user->assignRole($role);
        }

        // Return success response on succesfull request
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Method to get edit user view
    public function edit($id)
    {
        $this->authorize('edit_user');

        // Find or fail user by id
        $user = User::findOrFail($id);
        // Get all available roles
        $roles = Role::all();
        // Return edit user view
        return view('users.edit', compact('user', 'roles'));
    }

    // Method to put or update user info
    public function update(Request $request, $id)
    {
        $this->authorize('edit_user');

        // Validate role id
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|exists:roles,id'
        ]);

        // Find user or fail with id
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->rol_id = $request->input('role');
        // Save new user information
        $user->save();

        // Get the role name
        if ($request->has('role')) {
            $roleId = $request->input('role');
            $role = Role::find($roleId);

            // If role exists
            if ($role) {
                // Get role name to update user info
                $user->syncRoles($role->name); // Use the role name
            } else {
                // Return error message with invalid role
                return redirect()->route('users.edit', $id)
                    ->with('error', 'Rol seleccionado no es válido.');
            }
        } else {
            $user->syncRoles([]);
        }

        // If successfull request, return success message
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    // Method to delete or destroy user by id
    public function destroy($id)
    {
        $this->authorize('delete_user');

        $user = User::findOrFail($id);

        // Verify if user has role 'admin'
        if ($user->roles->first()->name === 'admin') {
            // Return to users page with error message
            return redirect()->route('users.index')->with('error', 'No se puede eliminar al usuario con rol de admin.');
        }

        // Execute user deletion
        $user->delete();

        // Return success message on successfull query
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

}
