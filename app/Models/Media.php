<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'url',
        'title',
        'description',
        'filename',
        'mime_type',
        'size'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
