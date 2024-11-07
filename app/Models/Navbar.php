<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'css',
        'is_active',
        'template_id',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'json'
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public static function findOrCreate($id = null, array $attributes = [])
    {
        if ($id) {
            $navbar = static::find($id);
            if ($navbar) {
                $navbar->update($attributes);
                return $navbar;
            }
        }

        // Ensure settings is JSON encoded if it's an array
        if (isset($attributes['settings']) && is_array($attributes['settings'])) {
            $attributes['settings'] = json_encode($attributes['settings']);
        }

        return static::create($attributes);
    }
}
