<?php

namespace App\Plugins;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Plugin;

class PluginManager
{
    private static $instance = null;
    private $loadedPlugins = [];
    private $hooks = [];

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function loadPlugins(): void
    {
        $plugins = Plugin::where('is_active', true)->get();

        foreach ($plugins as $plugin) {
            try {
                $this->loadPlugin($plugin);
            } catch (\Exception $e) {
                Log::error("Failed to load plugin {$plugin->name}: " . $e->getMessage());
            }
        }
    }

    private function loadPlugin(Plugin $plugin): void
    {
        $mainClass = $plugin->main_class;
        $fullClassName = "Plugins\\{$plugin->name}\\src\\{$mainClass}";

        if (!class_exists($fullClassName)) {
            throw new \Exception("Plugin main class not found: {$fullClassName}");
        }

        $instance = new $fullClassName();
        $this->loadedPlugins[$plugin->name] = $instance;

        if (method_exists($instance, 'boot')) {
            $instance->boot();
        }
    }

    public function addHook(string $hookName, callable $callback): void
    {
        if (!isset($this->hooks[$hookName])) {
            $this->hooks[$hookName] = new Collection();
        }
        $this->hooks[$hookName]->push($callback);
    }

    public function executeHook(string $hookName, ...$args)
    {
        if (!isset($this->hooks[$hookName])) {
            return null;
        }

        $results = new Collection();
        foreach ($this->hooks[$hookName] as $callback) {
            try {
                $results->push($callback(...$args));
            } catch (\Exception $e) {
                Log::error("Hook execution failed: {$hookName}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return $results;
    }

    public function getLoadedPlugins(): array
    {
        return $this->loadedPlugins;
    }
}
