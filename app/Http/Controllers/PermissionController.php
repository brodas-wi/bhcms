<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        // $permissions = Permission::all(); // Get all permissions from db
        $permissions = Permission::paginate(10);

        // Get value from 'perPage' from request or default value 5
        $perPage = $request->input('perPage', 5);

        // Paginate permissions with selected quantity
        $permissions = Permission::paginate($perPage);

        return view('permissions.index', compact('permissions')); // Return permissions data to view
    }

    // Function to get create permissions view
    public function create()
    {
        return view('permissions.create');
    }

    // Function to post permissions on db
    public function store(Request $request)
    {
        // Validate inputs for request
        $request->validate([
            'name' => 'required|unique:permissions,name|max:255',
        ]);

        // Create permissions request, send data to db
        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // Set default guard
        ]);

        // Return route if query is valid
        return redirect()->route('permissions.index')->with('success', 'Permiso creado exitosamente.');
    }

    // Method to destroy or delete permission by id
    public function destroy($id)
    {
        // Find or fail permission by requested id
        $permission = Permission::findOrFail($id);

        // Verify if permissions has role associated
        $rolesWithPermission = $permission->roles()->count();

        // If found role associated with this permission
        if ($rolesWithPermission > 0) {
            // Do not delete, return error message
            return redirect()->route('permissions.index')->with('error', 'No se puede eliminar el permiso "' . $permission->name . '" porque está asociado a uno o más roles.');
        }

        // If not exists associated roles, delete permission
        $permission->delete();

        // On successful request return successfull message
        return redirect()->route('permissions.index')->with('success', 'Permiso "' . $permission->name . '" eliminado correctamente.');
    }
}
