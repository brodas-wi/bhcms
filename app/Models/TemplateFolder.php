<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TemplateFolder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($folder) {
            if (empty($folder->slug)) {
                $folder->slug = Str::slug($folder->name);
            }
        });
    }

    /**
     * Get the templates for the folder.
     */
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    /**
     * Create a unique slug for the folder.
     *
     * @param string $name
     * @return string
     */
    public static function createUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the folder's template count.
     *
     * @return int
     */
    public function getTemplateCountAttribute()
    {
        return $this->templates()->count();
    }

    /**
     * Scope a query to order folders by name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Check if the folder has any templates.
     *
     * @return bool
     */
    public function hasTemplates()
    {
        return $this->templates()->exists();
    }

    /**
     * Delete the folder and optionally its templates.
     *
     * @param bool $deleteTemplates
     * @return bool|null
     */
    public function deleteWithTemplates($deleteTemplates = false)
    {
        if ($deleteTemplates) {
            $this->templates()->delete();
        } else {
            $this->templates()->update(['template_folder_id' => null]);
        }

        return $this->delete();
    }
}
