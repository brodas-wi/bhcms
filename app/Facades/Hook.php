<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Hook extends Facade
{
    // Method to get HookSystem Facade Access
    protected static function getFacadeAccessor()
    {
        return 'App\Services\HookSystem';
    }
}
