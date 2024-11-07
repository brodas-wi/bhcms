<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Services\HookSystem;
use App\Services\ContentService;
use App\Models\Plugin;
use App\Services\HtmlFormatterService;
use App\Interfaces\PluginInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HookSystem::class, function ($app) {
            return HookSystem::getInstance();
        });

        // Registrar ContentService
        $this->app->singleton(ContentService::class, function ($app) {
            return new ContentService();
        });

        $this->app->singleton(HtmlFormatterService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('plugins')) {
                $this->loadPlugins();
            }
        } catch (\Exception $e) {
            Log::error('Error in AppServiceProvider: ' . $e->getMessage());
        }
    }

    /**
     * Load and initialize active plugins.
     */
    protected function loadPlugins(): void
    {
        try {
            $plugins = Plugin::where('is_active', true)->get();
            $hookSystem = $this->app->make(HookSystem::class);

            foreach ($plugins as $plugin) {
                $this->loadPlugin($plugin, $hookSystem);
            }
        } catch (\Exception $e) {
            Log::error('Error loading plugins: ' . $e->getMessage());
        }
    }

    /**
     * Load a single plugin.
     */
    protected function loadPlugin(Plugin $plugin, HookSystem $hookSystem): void
    {
        try {
            // Register view namespace for the plugin
            $viewPath = base_path("plugins/{$plugin->name}/resources/views");
            if (file_exists($viewPath)) {
                View::addNamespace($plugin->name, $viewPath);
                Log::info("Registered view namespace for plugin: {$plugin->name}");
            } else {
                Log::warning("View path not found for plugin: {$plugin->name}");
            }

            // Load the main class of the plugin
            $mainClass = $plugin->main_class;
            $fullClassName = "Plugins\\{$plugin->name}\\src\\{$mainClass}";

            if (class_exists($fullClassName)) {
                $instance = new $fullClassName();

                if (method_exists($instance, 'register')) {
                    $instance->register($hookSystem);
                }

                if (method_exists($instance, 'boot')) {
                    $instance->boot();
                }

                // Register hooks
                if (!empty($plugin->selected_hooks)) {
                    foreach ($plugin->selected_hooks as $hook) {
                        $hookSystem->addAction($hook, [$instance, 'render']);
                        Log::info("Registered hook '{$hook}' for plugin: {$plugin->name}");
                    }
                } else {
                    Log::warning("No hooks selected for plugin: {$plugin->name}");
                }

                Log::info("Successfully loaded plugin: {$plugin->name}");
            } else {
                Log::error("Main class not found for plugin: {$plugin->name}. Tried to load: {$fullClassName}");
            }
        } catch (\Exception $e) {
            Log::error("Error loading plugin {$plugin->name}: " . $e->getMessage());
        }
    }

    /**
     * Check if all dependencies are satisfied.
     */
    private function areDependenciesSatisfied(array $dependencies): bool
    {
        foreach ($dependencies as $dependency) {
            if (!Plugin::where('name', $dependency)->where('is_active', true)->exists()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Deactivate a plugin and log the reason.
     */
    private function deactivatePlugin(Plugin $plugin): void
    {
        Log::warning("Plugin {$plugin->name} was deactivated due to unsatisfied dependencies or errors.");
        $plugin->is_active = false;
        $plugin->save();
    }
}
