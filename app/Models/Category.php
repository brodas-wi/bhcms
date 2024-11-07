<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id'
    ];

    // Mantener la relación existente con articles
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    // Agregar la nueva relación con contents
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_category', 'category_id', 'content_id');
    }

    // Relaciones padre/hijo
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Método helper para obtener lista de categorías
    // Método simplificado que devuelve una colección de categorías con nombres indentados
    public static function getSelectList(): Collection
    {
        return static::orderBy('name')
            ->get()
            ->map(function ($category) {
                $category->name = self::getIndentedName($category);
                return $category;
            });
    }

    // Método helper para obtener el nombre indentado
    private static function getIndentedName($category): string
    {
        $depth = 0;
        $parent = $category->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return str_repeat('— ', $depth) . $category->name;
    }

    // Método para verificar si tiene hijos
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
