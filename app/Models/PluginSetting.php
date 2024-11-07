<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PluginSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public function plugin()
    {
        return $this->belongsTo(Plugin::class);
    }
}
