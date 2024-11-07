<?php

namespace Plugins\ChatFloating\src;

use App\Plugins\BasePlugin;
use App\Services\HookSystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ChatFloating extends BasePlugin
{
    protected $config;
    protected static $registered = false;

    public function register(HookSystem $hookSystem): void
    {
        // Registrar el hook
        $hookSystem->addAction('after_footer', function () {
            return $this->render();
        });

        // Registrar el namespace de las vistas
        View::addNamespace('ChatFloating', base_path('plugins/ChatFloating/resources/views'));
    }

    protected function loadConfig(): void
    {
        $configPath = base_path('plugins/ChatFloating/config/config.php');
        $this->config = File::exists($configPath)
            ? require $configPath
            : ['enabled' => true, 'chat_title' => 'Chat de Ejemplo'];
    }

    public function render(): string
    {
        try {
            $viewContent = view('ChatFloating::chat')->render();
            $cssContent = $this->getStyles();
            $jsContent = $this->getScripts();

            return <<<HTML
                <!-- Chat Floating Plugin Start -->
                <style>
                    {$cssContent}
                </style>
                {$viewContent}
                <script>
                    {$jsContent}
                </script>
                <!-- Chat Floating Plugin End -->
            HTML;
        } catch (\Exception $e) {
            \Log::error('Error rendering ChatFloating plugin: ' . $e->getMessage());
            return '';
        }
    }

    protected function getStyles(): string
    {
        $path = base_path('plugins/ChatFloating/resources/css/styles.css');
        return File::exists($path) ? File::get($path) : '';
    }

    protected function getScripts(): string
    {
        $path = base_path('plugins/ChatFloating/resources/js/scripts.js');
        return File::exists($path) ? File::get($path) : '';
    }

    public function getHooks(): array
    {
        return [
            'after_footer' => [$this, 'render']
        ];
    }

    public function boot(): void
    {
        // Registrar el namespace de las vistas del plugin
        View::addNamespace('ChatFloating', base_path('plugins/ChatFloating/resources/views'));
    }
}
