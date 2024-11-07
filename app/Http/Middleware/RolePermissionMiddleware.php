<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RolePermissionMiddleware
{
    public function handle(Request $request, Closure $next, $roleOrPermission)
    {
        $user = auth()->user();

        // Debug
        \Log::info('RolePermissionMiddleware check', [
            'user' => $user->id,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->permissions->pluck('name'),
            'required' => $roleOrPermission
        ]);

        if (empty($roleOrPermission)) {
            throw new UnauthorizedException(403, 'No role or permission specified');
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if ($user->hasAnyRole($rolesOrPermissions) || $user->hasAnyPermission($rolesOrPermissions)) {
            return $next($request);
        }

        throw new UnauthorizedException(403, 'User does not have any of the necessary access rights.');
    }
}
