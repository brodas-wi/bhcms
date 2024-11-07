<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plugin Path
    |--------------------------------------------------------------------------
    |
    | This value is the path where your plugins are stored. This path
    | is used by the plugin service to locate and load plugins.
    |
    */

    'path' => base_path('plugins'),

    /*
    |--------------------------------------------------------------------------
    | Plugin Namespace
    |--------------------------------------------------------------------------
    |
    | This value is the namespace prefix that should be used for all plugin
    | classes. This helps to avoid conflicts with other parts of your application.
    |
    */

    'namespace' => 'Plugins',

    /*
    |--------------------------------------------------------------------------
    | Auto-load Plugins
    |--------------------------------------------------------------------------
    |
    | This value determines whether plugins should be automatically loaded
    | when the application boots. If set to false, you'll need to manually
    | load plugins using the plugin service.
    |
    */

    'auto_load' => true,

    /*
    |--------------------------------------------------------------------------
    | Plugin Cache
    |--------------------------------------------------------------------------
    |
    | This value determines whether discovered plugins should be cached
    | to improve performance. Set this to false during development.
    |
    */

    'cache' => env('PLUGIN_CACHE', true),
];
