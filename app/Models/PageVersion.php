<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'content',
        'serialized_content',
        'active_plugins',
        'version',
        'created_by'
    ];

    protected $casts = [
        'serialized_content' => 'array',
        'active_plugins' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($version) {
            if (!$version->page->validateVersion($version->version)) {
                throw new \Exception('Formato de versión inválido');
            }
        });

        static::updating(function ($version) {
            if ($version->isDirty('version') && !$version->page->validateVersion($version->version)) {
                throw new \Exception('Formato de versión inválido');
            }
        });
    }

    public function isLatestVersion(): bool
    {
        return $this->version === $this->page->version;
    }

    public function canBeDeleted(): bool
    {
        return !$this->isLatestVersion() &&
            $this->version !== '1.0.0' &&
            $this->page->versions()->count() > 1;
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
