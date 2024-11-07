<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = ['title', 'url', 'page_id', 'parent_id', 'order'];

    public function parent()
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
