<?php

namespace Plugins\TimeTracker\Models;

use Illuminate\Database\Eloquent\Model;

class TimeRecord extends Model
{
    protected $fillable = [
        'record_time',
        'notes'
    ];

    protected $casts = [
        'record_time' => 'datetime'
    ];
}
