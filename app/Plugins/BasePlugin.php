<?php

namespace App\Plugins;

use App\Contracts\PluginInterface;
use Illuminate\Support\Facades\Route;
use App\Services\HookSystem;

abstract class BasePlugin implements PluginInterface
{
    protected $name;
    protected $description;
    protected $version = '1.0.0';
    protected $author;

    public function __construct()
    {
        $this->name = class_basename($this);
        $this->description = "A plugin for the CMS";
        $this->author = "Unknown";
    }

    public function register(HookSystem $hookSystem): void
    {
        // Implementación por defecto
    }

    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerHooks();
    }

    protected function registerHooks(): void
    {
        $hooks = $this->getHooks();
        $hookSystem = HookSystem::getInstance();

        foreach ($hooks as $hookName => $callback) {
            $hookSystem->addAction($hookName, $callback);
        }
    }

    public function getHooks(): array
    {
        return [];
    }

    public function activate(): void
    {
        // Implementación por defecto
    }

    public function deactivate(): void
    {
        // Implementación por defecto
    }

    public function uninstall(): void
    {
        // Implementación por defecto
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function getSettings(): array
    {
        return [];
    }

    public function updateSettings(array $settings): void
    {
        // Implementación por defecto
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    protected function registerRoutes(): void
    {
        $routesFile = $this->getPluginPath() . '/routes.php';
        if (file_exists($routesFile)) {
            Route::group(['prefix' => 'plugin/' . strtolower($this->getName())], function () use ($routesFile) {
                require $routesFile;
            });
        }
    }

    protected function getPluginPath(): string
    {
        $reflector = new \ReflectionClass($this);
        return dirname($reflector->getFileName());
    }
}
