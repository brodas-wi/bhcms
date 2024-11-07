<?php

namespace App\Services;

use App\Models\Plugin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class PluginServiceCopy
{
    protected $pluginsPath;

    public function __construct()
    {
        $this->pluginsPath = base_path('plugins');
    }

    private function stringToArray($string)
    {
        if (empty($string)) {
            return [];
        }
        return array_map('trim', explode("\n", $string));
    }

    public function createPlugin(array $data)
    {
        try {
            $formattedName = $this->formatPluginName($data['name']);
            $pluginPath = $this->pluginsPath . '/' . $formattedName;

            if (File::exists($pluginPath)) {
                throw new \Exception('A plugin with this name already exists.');
            }

            // Create plugin directory structure
            if (!File::makeDirectory($pluginPath . '/src', 0755, true)) {
                throw new \Exception('Failed to create plugin directory structure.');
            }
            File::makeDirectory($pluginPath . '/resources/views', 0755, true);
            File::makeDirectory($pluginPath . '/database/migrations', 0755, true);
            File::makeDirectory($pluginPath . '/config', 0755, true);

            // Create plugin.json
            $pluginJson = [
                'name' => $data['name'],
                'description' => $data['description'],
                'version' => $data['version'],
                'author' => $data['author'],
                'main_class' => $data['main_class'],
            ];

            if (!File::put($pluginPath . '/plugin.json', json_encode($pluginJson, JSON_PRETTY_PRINT))) {
                throw new \Exception('Failed to create plugin.json file.');
            }

            $hooks = is_array($data['hooks']) ? $data['hooks'] : $this->stringToArray($data['hooks']);

            // Create main plugin class
            $mainClassContent = $this->generateMainClassContent($formattedName, $data['main_class'], $hooks ?? []);
            if (!File::put($pluginPath . "/src/{$data['main_class']}.php", $mainClassContent)) {
                throw new \Exception('Failed to create main plugin class file.');
            }

            // Create config file
            $configContent = "<?php\n\nreturn " . var_export($data['config'] ?? [], true) . ";";
            File::put($pluginPath . '/config/config.php', $configContent);

            // Create migrations
            if (!empty($data['migrations'])) {
                foreach ($data['migrations'] as $migration) {
                    $migrationName = date('Y_m_d_His_') . Str::snake($migration) . '.php';
                    $migrationContent = $this->generateMigrationContent($migration);
                    File::put($pluginPath . '/database/migrations/' . $migrationName, $migrationContent);
                }
            }

            // Create views
            $views = [];
            if (!empty($data['views'])) {
                foreach ($data['views'] as $view) {
                    $viewContent = "<!-- {$view} view content for {$data['name']} plugin -->";
                    File::put($pluginPath . '/resources/views/' . $view . '.blade.php', $viewContent);
                    $views[] = $view;
                }
            }

            // Register plugin in database
            $plugin = Plugin::create([
                'name' => $formattedName,
                'original_name' => $data['name'],
                'description' => $data['description'],
                'version' => $data['version'],
                'author' => $data['author'],
                'is_active' => false,
                'views' => $views
            ]);

            $this->clearPluginCache();

            return $plugin;

        } catch (\Exception $e) {
            \Log::error('Error in createPlugin: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatePlugin(Plugin $plugin, array $data)
    {
        $plugin->fill($data);
        $plugin->save();

        $pluginPath = $this->pluginsPath . '/' . $plugin->name;

        // Update plugin.json
        $pluginJson = json_decode(File::get($pluginPath . '/plugin.json'), true);
        $pluginJson['description'] = $data['description'];
        $pluginJson['version'] = $data['version'];
        $pluginJson['author'] = $data['author'];
        File::put($pluginPath . '/plugin.json', json_encode($pluginJson, JSON_PRETTY_PRINT));

        // Update config file
        if (isset($data['config'])) {
            $configContent = "<?php\n\nreturn " . var_export($data['config'], true) . ";";
            File::put($pluginPath . '/config/config.php', $configContent);
        }

        // Update views
        if (isset($data['views'])) {
            $oldViews = $plugin->views;
            $newViews = $data['views'];

            // Remove old views that are not in the new list
            foreach ($oldViews as $oldView) {
                if (!in_array($oldView, $newViews)) {
                    File::delete($pluginPath . '/resources/views/' . $oldView . '.blade.php');
                }
            }

            // Add new views
            foreach ($newViews as $newView) {
                if (!in_array($newView, $oldViews)) {
                    $viewContent = "<!-- {$newView} view content for {$plugin->original_name} plugin -->";
                    File::put($pluginPath . '/resources/views/' . $newView . '.blade.php', $viewContent);
                }
            }

            $plugin->views = $newViews;
            $plugin->save();
        }

        $this->clearPluginCache();

        return $plugin;
    }

    public function deletePlugin(Plugin $plugin)
    {
        $pluginPath = $this->pluginsPath . '/' . $plugin->name;

        if (File::exists($pluginPath)) {
            File::deleteDirectory($pluginPath);
        }

        $plugin->delete();

        $this->clearPluginCache();
    }

    public function activatePlugin(Plugin $plugin)
    {
        $plugin->is_active = true;
        $plugin->save();

        $this->runPluginMigrations($plugin);
        $this->clearPluginCache();
    }

    public function deactivatePlugin(Plugin $plugin)
    {
        $plugin->is_active = false;
        $plugin->save();

        $this->clearPluginCache();
    }

    protected function formatPluginName($name)
    {
        return Str::studly($name);
    }

    protected function generateMainClassContent($pluginName, $mainClassName, $hooks)
    {
        $hooksContent = empty($hooks) ? '[]' : "[\n            '" . implode("',\n            '", $hooks) . "'\n        ]";

        return "<?php

namespace Plugins\\{$pluginName}\\src;

use App\Plugins\BasePlugin;

class {$mainClassName} extends BasePlugin
{
    public function register(): void
    {
        // Registration logic
    }

    public function boot(): void
    {
        // Boot logic
    }

    public function getHooks(): array
    {
        return {$hooksContent};
    }
}
";
    }

    protected function generateMigrationContent($migrationName)
    {
        return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class " . Str::studly($migrationName) . " extends Migration
{
    public function up()
    {
        // Migration up logic
    }

    public function down()
    {
        // Migration down logic
    }
}
";
    }

    protected function runPluginMigrations(Plugin $plugin)
    {
        $migrationPath = $this->pluginsPath . '/' . $plugin->name . '/database/migrations';

        if (File::exists($migrationPath)) {
            Artisan::call('migrate', [
                '--path' => str_replace(base_path(), '', $migrationPath),
                '--force' => true,
            ]);
        }
    }

    protected function clearPluginCache()
    {
        // Clear application cache
        Artisan::call('cache:clear');

        // You might want to add more cache clearing commands here
        // For example, clearing config cache, route cache, etc.
    }
}
