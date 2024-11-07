<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Plugin;
use Illuminate\Auth\Access\HandlesAuthorization;

class PluginPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si el usuario puede configurar el plugin.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plugin  $plugin
     * @return bool
     */
    public function configure(User $user, Plugin $plugin)
    {
        // Permitir a los administradores configurar cualquier plugin
        return $user->hasRole('admin');
    }

    /**
     * Determine si el usuario puede ver la lista de plugins.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // Permitir a los administradores ver la lista de plugins
        return $user->hasRole('admin');
    }

    /**
     * Determine si el usuario puede ver un plugin específico.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plugin  $plugin
     * @return bool
     */
    public function view(User $user, Plugin $plugin)
    {
        // Permitir a los administradores ver cualquier plugin
        return $user->hasRole('admin');
    }

    public function read(User $user, Plugin $plugin)
    {
        // Implementa la lógica de autorización aquí
        // Por ejemplo, podrías permitir que todos los usuarios autenticados lean plugins:
        return true;

        // O podrías restringirlo a ciertos roles o permisos:
        // return $user->hasPermission('read-plugins');
    }

    // Puedes añadir más métodos según sea necesario para otras acciones como 'create', 'update', 'delete', etc.
}
