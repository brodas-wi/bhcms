<?php

namespace App\Services;

use App\Models\Plugin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PluginService
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
            DB::beginTransaction();

            $formattedName = $this->formatPluginName($data['name']);
            $pluginPath = $this->pluginsPath . '/' . $formattedName;

            if (File::exists($pluginPath)) {
                throw new \Exception('A plugin with this name already exists.');
            }

            $this->createPluginDirectoryStructure($pluginPath);

            // Process migrations from textarea
            if (!empty($data['migrations'])) {
                $migrations = array_filter(explode("\n", str_replace("\r", "", $data['migrations'])));
                foreach ($migrations as $migration) {
                    $this->createMigration($pluginPath, trim($migration));
                }
            }

            // Process model if checkbox is checked
            if (!empty($data['create_model']) && !empty($data['model_name'])) {
                $this->createModel($pluginPath, trim($data['model_name']), $formattedName);
            }

            // Process controller if checkbox is checked
            if (!empty($data['create_controller']) && !empty($data['controller_name'])) {
                $this->createController($pluginPath, trim($data['controller_name']), $formattedName);
            }

            // Process views from textarea
            if (!empty($data['views'])) {
                $views = array_filter(explode("\n", str_replace("\r", "", $data['views'])));
                foreach ($views as $view) {
                    $this->createView($pluginPath, trim($view));
                }
            }

            // Rest of your existing plugin creation code...
            $this->createPluginJson($pluginPath, $data);
            $this->createConfigFile($pluginPath, $data);
            $this->createMainPluginClass($pluginPath, $data, $formattedName);

            $plugin = Plugin::create([
                'name' => $formattedName,
                'original_name' => $data['name'],
                'description' => $data['description'],
                'version' => $data['version'],
                'author' => $data['author'],
                'main_class' => $data['main_class'],
                'is_active' => false,
                'is_global' => $data['is_global'] ?? false,
                'views' => $views ?? [],
                'selected_hooks' => $data['selected_hooks'] ?? []
            ]);

            DB::commit();
            $this->clearPluginCache();
            return $plugin;

        } catch (\Exception $e) {
            DB::rollBack();
            if (File::exists($pluginPath)) {
                File::deleteDirectory($pluginPath);
            }
            throw $e;
        }
    }

    protected function createMigration($pluginPath, $migrationName)
    {
        $timestamp = date('Y_m_d_His_');
        $fileName = $timestamp . Str::snake($migrationName) . '.php';
        $migrationContent = $this->generateMigrationStub($migrationName);

        File::put($pluginPath . '/database/migrations/' . $fileName, $migrationContent);
    }

    protected function createController($pluginPath, $controllerName, $pluginName)
    {
        $controllerContent = $this->generateControllerStub($controllerName, $pluginName);
        File::put($pluginPath . "/Controllers/{$controllerName}.php", $controllerContent);
    }

    protected function createView($pluginPath, $viewName)
    {
        $viewContent = $this->generateViewStub($viewName);
        $viewPath = $pluginPath . '/resources/views/' . $viewName . '.blade.php';
        File::ensureDirectoryExists(dirname($viewPath));
        File::put($viewPath, $viewContent);
    }

    protected function generateMigrationStub($name)
    {
        $className = Str::studly($name);
        $tableName = Str::snake(Str::pluralStudly($name));

        return <<<PHP
            <?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            class {$className} extends Migration
            {
                public function up()
                {
                    Schema::create('{$tableName}', function (Blueprint \$table) {
                        \$table->id();
                        // Add your columns here
                        \$table->timestamps();
                    });
                }

                public function down()
                {
                    Schema::dropIfExists('{$tableName}');
                }
            }
            PHP;
    }

    protected function generateControllerStub($controllerName, $pluginName)
    {
        return <<<PHP
            <?php

            namespace Plugins\\{$pluginName}\\Controllers;

            use App\Http\Controllers\Controller;
            use Illuminate\Http\Request;

            class {$controllerName} extends Controller
            {
                public function index()
                {
                    return view('{$pluginName}::index');
                }

                public function store(Request \$request)
                {
                    // Add store logic
                }
            }
            PHP;
    }

    protected function generateViewStub($viewName)
    {
        return <<<BLADE
            @extends('layouts.app')

            @section('content')
            <div class="container">
                <h1>{{ config('app.name') }}</h1>
                <!-- {$viewName} content -->
            </div>
            @endsection
            BLADE;
    }

    protected function createPluginDirectoryStructure($pluginPath)
    {
        File::makeDirectory($pluginPath . '/src', 0755, true);
        File::makeDirectory($pluginPath . '/resources/views', 0755, true);
        File::makeDirectory($pluginPath . '/database/migrations', 0755, true);
        File::makeDirectory($pluginPath . '/config', 0755, true);
        File::makeDirectory($pluginPath . '/Models', 0755, true);
        File::makeDirectory($pluginPath . '/Controllers', 0755, true);

        // Generate folders for assets
        File::makeDirectory($pluginPath . '/resources/css', 0755, true);
        File::makeDirectory($pluginPath . '/resources/js', 0755, true);

        // Generate default files for assets
        $this->createDefaultAssets($pluginPath);
    }

    protected function createDefaultAssets($pluginPath)
    {
        // Crear archivo CSS por defecto
        $defaultCss = $this->generateDefaultCss();
        File::put($pluginPath . '/resources/css/styles.css', $defaultCss);

        // Crear archivo JS por defecto
        $defaultJs = $this->generateDefaultJs();
        File::put($pluginPath . '/resources/js/scripts.js', $defaultJs);
    }

    protected function generateDefaultCss()
    {
        return <<<CSS
        /* Estilos del plugin */
        .plugin-container {
            margin: 15px;
            padding: 15px;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .plugin-container {
                margin: 10px;
                padding: 10px;
            }
        }
        CSS;
    }

    protected function generateDefaultJs()
    {
        return <<<JS
        // Scripts del plugin
        document.addEventListener('DOMContentLoaded', function() {
            // Inicialización del plugin
            console.log('Plugin inicializado');

            // Aquí puedes añadir tu código JavaScript
        });

        // Funciones auxiliares del plugin
        function initializePlugin() {
            // Código de inicialización
        }
        JS;
    }

    protected function createPluginJson($pluginPath, $data)
    {
        // Create plugin.json
        $pluginJson = [
            'name' => $data['name'],
            'description' => $data['description'],
            'version' => $data['version'],
            'author' => $data['author'],
            'main_class' => $data['main_class'],
            'selected_hooks' => $data['selected_hooks'],
            'config' => json_decode($data['config'], true),
        ];

        if (!File::put($pluginPath . '/plugin.json', json_encode($pluginJson, JSON_PRETTY_PRINT))) {
            throw new \Exception('Failed to create plugin.json file.');
        }
    }

    protected function createMainPluginClass($pluginPath, $data, $formattedName)
    {
        // Create main plugin class
        $mainClassContent = $this->generateMainClassContent($formattedName, $data['main_class'], $hooks ?? []);
        if (!File::put($pluginPath . "/src/{$data['main_class']}.php", $mainClassContent)) {
            throw new \Exception('Failed to create main plugin class file.');
        }
    }

    protected function createConfigFile($pluginPath, $data)
    {
        // Create config file
        $configContent = "<?php\n\nreturn " . var_export($data['config'] ?? [], true) . ";";
        File::put($pluginPath . '/config/config.php', $configContent);
    }

    protected function createModel($pluginPath, $modelName, $pluginName)
    {
        $modelContent = <<<PHP
        <?php

        namespace Plugins\\{$pluginName}\\Models;

        use Illuminate\Database\Eloquent\Model;

        class {$modelName} extends Model
        {
            protected \$fillable = [
                // Define your fillable attributes here
            ];

            protected \$casts = [
                'created_at' => 'datetime',
                'updated_at' => 'datetime',
            ];

            // Define your model relationships and other methods here
        }
        PHP;

        File::put($pluginPath . "/Models/{$modelName}.php", $modelContent);
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

        DB::beginTransaction();

        try {
            // Delete the plugin directory
            if (File::exists($pluginPath)) {
                if (!File::deleteDirectory($pluginPath)) {
                    throw new \Exception("Failed to delete plugin directory: {$pluginPath}");
                }
                Log::info("Deleted plugin directory: {$pluginPath}");
            } else {
                Log::warning("Plugin directory not found: {$pluginPath}");
            }

            // Delete the plugin from the database
            $plugin->delete();
            Log::info("Deleted plugin from database: {$plugin->name}");

            // Clear the plugin cache
            $this->clearPluginCache();

            DB::commit();
            Log::info("Successfully deleted plugin: {$plugin->name}");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete plugin {$plugin->name}: " . $e->getMessage());
            throw $e;
        }
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

        return <<<PHP
            <?php

            namespace Plugins\\{$mainClassName}\\src;

            use App\Plugins\BasePlugin;
            use App\Services\HookSystem;
            use Illuminate\Support\Facades\Route;
            use Illuminate\Support\Facades\File;

            class {$mainClassName} extends BasePlugin
            {
                protected \$config;

                public function register(HookSystem \$hookSystem): void
                {
                    // Registrar los hooks del plugin
                    foreach (\$this->getHooks() as \$hook) {
                        \$hookSystem->addAction(\$hook, [\$this, 'render']);
                    }

                    // Acceder a la configuración del plugin
                    \$this->config = config('plugins.{$pluginName}.config', []);

                    // Registrar las rutas del plugin
                    \$this->registerRoutes();

                    // Cargar assets del plugin
                    \$this->loadAssets();
                }

                public function boot(): void
                {
                    // Lógica de inicialización del plugin
                }

                protected function registerRoutes(): void
                {
                    Route::group(['prefix' => 'plugins/{$pluginName}'], function () {
                        // Definir las rutas del plugin aquí
                    });
                }

                public function getHooks(): array
                {
                    return {$hooksContent};
                }

                public function render(): string
                {
                    return view('{$pluginName}::main', [
                        'config' => \$this->config
                    ])->render();
                }

                protected function loadAssets(): void
                {
                    // Cargar CSS
                    \$cssPath = base_path("plugins/{$pluginName}/resources/css/styles.css");
                    if (File::exists(\$cssPath)) {
                        \$css = File::get(\$cssPath);
                        echo "<style>{{\$css}}</style>";
                    }

                    // Cargar JavaScript
                    \$jsPath = base_path("plugins/{$pluginName}/resources/js/scripts.js");
                    if (File::exists(\$jsPath)) {
                        \$js = File::get(\$jsPath);
                        echo "<script>{\$js}</script>";
                    }
                }

                public function getAssets(): array
                {
                    return [
                        'css' => File::exists(base_path("plugins/{$pluginName}/resources/css/styles.css"))
                            ? File::get(base_path("plugins/{$pluginName}/resources/css/styles.css"))
                            : '',
                        'js' => File::exists(base_path("plugins/{$pluginName}/resources/js/scripts.js"))
                            ? File::get(base_path("plugins/{$pluginName}/resources/js/scripts.js"))
                            : ''
                    ];
                }
            }
            PHP;
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

    public function verifyPluginStructure(Plugin $plugin)
    {
        $pluginPath = base_path('plugins/' . $plugin->name);
        $errors = [];

        // Verificar estructura de directorios
        $requiredDirs = ['src', 'resources/views', 'database/migrations', 'config', 'Models', 'Controllers'];
        foreach ($requiredDirs as $dir) {
            if (!File::isDirectory($pluginPath . '/' . $dir)) {
                $errors[] = "Directorio faltante: {$dir}";
            }
        }

        // Verificar archivos principales
        $requiredFiles = ['plugin.json', "src/{$plugin->main_class}.php", 'config/config.php'];
        foreach ($requiredFiles as $file) {
            if (!File::exists($pluginPath . '/' . $file)) {
                $errors[] = "Archivo faltante: {$file}";
            }
        }

        // Verificar contenido de plugin.json
        $pluginJson = json_decode(File::get($pluginPath . '/plugin.json'), true);
        if (!$pluginJson || !isset($pluginJson['name'], $pluginJson['description'], $pluginJson['version'], $pluginJson['author'], $pluginJson['main_class'])) {
            $errors[] = "plugin.json incompleto o mal formado";
        }

        return $errors;
    }
}
