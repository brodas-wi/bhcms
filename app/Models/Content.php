<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'type',
        'status',
        'published_at',
        'user_id',
        'featured_image',
        'excerpt',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Corregir la relación con categories especificando la tabla pivote
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'content_category', 'content_id', 'category_id');
    }

    // Corregir la relación con tags especificando la tabla pivote
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag', 'content_id', 'tag_id');
    }

    public function versions()
    {
        return $this->hasMany(ContentVersion::class);
    }

    public function createVersion()
    {
        return $this->versions()->create([
            'content' => $this->content,
            'version_number' => $this->versions()->count() + 1,
            'user_id' => auth()->id()
        ]);
    }
}
