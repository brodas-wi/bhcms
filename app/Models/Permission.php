<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

class Permission extends Model
{
    use HasFactory;

    // Link permissions table
    protected $table = 'permissions';

    // Set attributes to make a massive fill
    protected $fillable = [
        'name',
        'guard_name',
    ];

    // Function to make link with Role Model
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
}
