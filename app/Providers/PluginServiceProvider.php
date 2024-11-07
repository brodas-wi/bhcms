<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Plugins\PluginManager;

class PluginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Inicializar y cargar plugins
        PluginManager::getInstance()->loadPlugins();

        // Cargar recursos de plugins activos
        $this->loadPluginResources();
    }

    private function loadPluginResources()
    {
        $pluginsPath = base_path('plugins');

        if (File::isDirectory($pluginsPath)) {
            $plugins = File::directories($pluginsPath);

            foreach ($plugins as $plugin) {
                $pluginName = basename($plugin);

                // Cargar vistas
                $viewsPath = $plugin . '/resources/views';
                if (File::isDirectory($viewsPath)) {
                    $this->loadViewsFrom($viewsPath, "plugins.{$pluginName}");
                }

                // Cargar configuraciones
                $configPath = $plugin . '/config/config.php';
                if (File::exists($configPath)) {
                    $this->mergeConfigFrom($configPath, "plugins.{$pluginName}");
                }
            }
        }
    }

    public function register()
    {
        $this->app->singleton(PluginManager::class, function () {
            return PluginManager::getInstance();
        });
    }
}
