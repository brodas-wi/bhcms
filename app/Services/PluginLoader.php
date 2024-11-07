<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use App\Models\Plugin;
use Illuminate\Support\Facades\Log;

class PluginLoader
{
    // Method to find and load plugins from root plugins folder
    // public function loadPlugins()
    // {
    //     $pluginsPath = base_path('plugins');
    //     $plugins = File::directories($pluginsPath);

    //     foreach ($plugins as $pluginPath) {
    //         $this->registerPlugin($pluginPath);
    //     }
    // }

    private function formatPluginName($name)
    {
        // Elimina caracteres especiales y espacios, luego aplica StudlyCase
        $name = preg_replace('/[^a-zA-Z0-9]+/', ' ', $name);
        return str_replace(' ', '', ucwords($name));
    }

    public function loadPlugins()
    {
        $pluginDirectories = [
            base_path('plugins'),
            base_path('vendor/third-party-plugins')
        ];

        foreach ($pluginDirectories as $directory) {
            $plugins = File::directories($directory);
            foreach ($plugins as $pluginPath) {
                $this->loadPlugin(basename($pluginPath));
            }
        }

        // Cargar plugins globales primero
        $plugins = Plugin::where('is_active', true)->orderBy('is_global', 'desc')->get();

        foreach ($plugins as $plugin) {
            $this->loadPlugin($plugin->name);
        }
    }

    public function loadPlugin($pluginName)
    {
        $formattedName = $this->formatPluginName($pluginName);
        $pluginPath = base_path("plugins/{$formattedName}");

        Log::info("Attempting to load plugin: {$formattedName}");
        Log::info("Plugin path: {$pluginPath}");

        if (!File::isDirectory($pluginPath)) {
            Log::error("Plugin directory not found: {$pluginPath}");
            throw new \Exception("Plugin directory not found: {$pluginPath}");
        }

        $providerClass = "Plugins\\{$formattedName}\\PluginServiceProvider";
        $providerPath = "{$pluginPath}/PluginServiceProvider.php";

        Log::info("Looking for provider: {$providerPath}");

        if (File::exists($providerPath)) {
            Log::info("Provider file found, attempting to load");
            require_once $providerPath;
            if (class_exists($providerClass)) {
                Log::info("Provider class exists, registering");
                app()->register($providerClass);
            } else {
                Log::error("Provider class not found: {$providerClass}");
                throw new \Exception("Plugin service provider class not found: {$providerClass}");
            }
        } else {
            Log::error("Provider file not found: {$providerPath}");
            throw new \Exception("Plugin service provider file not found: {$providerPath}");
        }

        // Cargar rutas del plugin si existen
        $routesPath = "{$pluginPath}/routes.php";
        if (File::exists($routesPath)) {
            require $routesPath;
        }

        // Aquí puedes agregar más lógica de carga según tus necesidades
        // Por ejemplo, cargar vistas, traducciones, etc.
    }

    // Method to register new plugins, created or third party
    protected function registerPlugin($pluginPath)
    {
        $pluginName = basename($pluginPath);
        $providerClass = "Plugins\\{$pluginName}\\PluginServiceProvider";

        if (class_exists($providerClass)) {
            app()->register($providerClass);
        }
    }
}
