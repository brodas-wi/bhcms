<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class Plugin extends Model
{
    protected $fillable = [
        'name',
        'original_name',
        'description',
        'version',
        'author',
        'main_class',
        'is_active',
        'is_global',
        'views',
        'selected_hooks'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_global' => 'boolean',
        'views' => 'array',
        'selected_hooks' => 'array'
    ];

    /**
     * Get the plugin's main class instance.
     *
     * @return mixed
     */
    public function getInstance()
    {
        $pluginPath = base_path('plugins/' . $this->name);
        $configPath = $pluginPath . '/plugin.json';

        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true);
            $className = $config['main_class'];
            $fullClassName = "Plugins\\{$this->name}\\src\\{$className}";

            \Log::info("Attempting to instantiate plugin class: {$fullClassName}");

            if (class_exists($fullClassName)) {
                return new $fullClassName();
            } else {
                \Log::error("Class not found: {$fullClassName}");
            }
        } else {
            \Log::error("Config file not found for plugin: {$this->name}");
        }

        return null;
    }

    public function renderViewForEditor($viewName, $data = [])
    {
        $pluginName = Str::studly($this->name);

        // Registrar el namespace de la vista
        $viewsPath = base_path("plugins/{$pluginName}/resources/views");
        View::addNamespace($pluginName, $viewsPath);

        // Obtener datos del controlador
        $controllerClass = "Plugins\\{$pluginName}\\Controllers\\{$pluginName}Controller";
        if (class_exists($controllerClass)) {
            $controller = app()->make($controllerClass);
            if (method_exists($controller, $viewName)) {
                $data = array_merge($data, $controller->$viewName());
            } elseif (method_exists($controller, 'getData')) {
                $data = array_merge($data, $controller->getData($viewName));
            } elseif (method_exists($controller, 'index')) {
                $data = array_merge($data, $controller->index());
            }
        }

        return View::make("{$pluginName}::{$viewName}", $data)->render();
    }

    // public function renderViewForEditor($viewName, $data = [])
    // {
    //     $pluginName = Str::studly($this->name);
    //     $viewPath = base_path("plugins/{$pluginName}/resources/views/{$viewName}.blade.php");

    //     Log::info("Attempting to render view for editor", [
    //         'plugin_name' => $this->name,
    //         'view_name' => $viewName,
    //         'view_path' => $viewPath
    //     ]);

    //     if (!file_exists($viewPath)) {
    //         Log::error("View file not found", ['view_path' => $viewPath]);
    //         throw new \Exception("Vista '{$viewName}' no encontrada para el plugin '{$this->name}'.");
    //     }

    //     // Registrar el directorio de vistas del plugin
    //     $viewsPath = base_path("plugins/{$pluginName}/resources/views");
    //     View::addNamespace($pluginName, $viewsPath);

    //     // Obtener datos del plugin
    //     $pluginInstance = $this->getInstance();
    //     $pluginData = [];
    //     if ($pluginInstance && method_exists($pluginInstance, 'getViewData')) {
    //         try {
    //             $pluginData = $pluginInstance->getViewData($viewName);
    //         } catch (\Exception $e) {
    //             Log::error("Error getting view data from plugin", [
    //                 'plugin_name' => $this->name,
    //                 'error' => $e->getMessage()
    //             ]);
    //         }
    //     }

    //     // Combinar datos del plugin con datos adicionales
    //     $mergedData = array_merge($pluginData, $data);

    //     Log::info("Rendering view for editor with data", [
    //         'plugin_name' => $this->name,
    //         'view_name' => $viewName,
    //         'data' => $mergedData
    //     ]);

    //     return View::make("{$pluginName}::{$viewName}", $mergedData)->render();
    // }

    /**
     * Render a specific view of the plugin.
     *
     * @param string $viewName
     * @param array $data
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderView($viewName = 'index', $data = [])
    {
        try {
            \Log::debug("Attempting to render plugin view", [
                'plugin' => $this->name,
                'view' => $viewName,
                'views_directory' => base_path("plugins/{$this->name}/resources/views")
            ]);

            // Asegurarse de que el nombre del plugin esté en el formato correcto
            $pluginName = Str::studly($this->name);

            // Registrar el namespace de vistas para el plugin
            View::addNamespace($pluginName, base_path("plugins/{$pluginName}/resources/views"));

            // Diferentes intentos de encontrar la vista
            $possibleViewPaths = [
                "{$pluginName}::{$viewName}",        // TimeTracker::index
                "{$pluginName}.{$viewName}",         // TimeTracker.index
                "plugins.{$pluginName}.{$viewName}", // plugins.TimeTracker.index
                $viewName                            // index
            ];

            $viewPath = null;
            foreach ($possibleViewPaths as $path) {
                if (View::exists($path)) {
                    $viewPath = $path;
                    break;
                }
            }

            if (!$viewPath) {
                throw new \Exception("View {$viewName} not found for plugin {$this->name}. Tried paths: " . implode(', ', $possibleViewPaths));
            }

            \Log::debug("Found view path", [
                'plugin' => $this->name,
                'view_path' => $viewPath
            ]);

            // Obtener datos del controlador si existe
            $controllerData = $this->getControllerData($viewName);

            // Combinar datos
            $mergedData = array_merge($controllerData, $data);

            return View::make($viewPath, $mergedData)->render();

        } catch (\Exception $e) {
            \Log::error("Error rendering plugin view", [
                'plugin' => $this->name,
                'view' => $viewName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    protected function getControllerData($viewName)
    {
        try {
            $controllerClass = "Plugins\\{$this->name}\\Controllers\\{$this->name}Controller";

            if (class_exists($controllerClass)) {
                $controller = app()->make($controllerClass);

                // Intentar diferentes métodos para obtener datos
                if (method_exists($controller, $viewName)) {
                    return $controller->{$viewName}();
                }

                if (method_exists($controller, 'getData')) {
                    return $controller->getData($viewName);
                }

                if (method_exists($controller, 'index')) {
                    return $controller->index();
                }
            }

            return [];
        } catch (\Exception $e) {
            \Log::warning("Error getting controller data for plugin {$this->name}: {$e->getMessage()}");
            return [];
        }
    }

    public function getStyles()
    {
        $stylesPath = base_path("plugins/{$this->name}/resources/css/styles.css");
        if (File::exists($stylesPath)) {
            return File::get($stylesPath);
        }
        return '';
    }

    public function getScripts()
    {
        $scriptsPath = base_path("plugins/{$this->name}/resources/js/scripts.js");
        if (File::exists($scriptsPath)) {
            return File::get($scriptsPath);
        }
        return '';
    }

    /**
     * Get the configuration for this plugin.
     *
     * @return array
     */
    public function getConfig()
    {
        $configPath = base_path("plugins/{$this->name}/config/config.php");

        if (File::exists($configPath)) {
            return require $configPath;
        }

        return [];
    }

    /**
     * Get all hooks for this plugin.
     *
     * @return array
     */
    public function getHooks()
    {
        $instance = $this->getInstance();

        if ($instance && method_exists($instance, 'getHooks')) {
            return $instance->getHooks();
        }

        return [];
    }

    /**
     * Activate the plugin.
     *
     * @return void
     */
    public function activate()
    {
        $this->is_active = true;
        $this->save();

        $instance = $this->getInstance();
        if ($instance && method_exists($instance, 'activate')) {
            $instance->activate();
        }
    }

    /**
     * Deactivate the plugin.
     *
     * @return void
     */
    public function deactivate()
    {
        $this->is_active = false;
        $this->save();

        $instance = $this->getInstance();
        if ($instance && method_exists($instance, 'deactivate')) {
            $instance->deactivate();
        }
    }

    public function isPluginActive($pluginId)
    {
        return is_array($this->active_plugins) && in_array($pluginId, $this->active_plugins);
    }

    /**
     * Scope a query to only include active plugins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the plugin's file path.
     *
     * @return string
     */
    public function getPath()
    {
        return base_path('plugins/' . $this->name);
    }
}
