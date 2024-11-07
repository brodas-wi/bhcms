<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Services\PluginService;
use App\Plugins\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class PluginController extends Controller
{
    protected $pluginService;
    protected $pluginManager;

    public function __construct(PluginService $pluginService, PluginManager $pluginManager)
    {
        $this->pluginService = $pluginService;
        $this->pluginManager = $pluginManager;
    }

    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $plugins = Plugin::all();
            return view('plugins.index', compact('plugins'));
        } catch (\Exception $e) {
            Log::error('Error al cargar plugins: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar plugins');
        }
    }

    public function create()
    {
        return view('plugins.create', [
            'availableHooks' => $this->getAvailableHooks(),
            'userName' => Auth::user()->name
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validatePluginData($request);
            $plugin = $this->pluginService->createPlugin($validated);
            return redirect()->route('plugin.index')->with('success', 'El plugin ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('La creación del plugin ha fallado: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Plugin $plugin)
    {
        return view('plugins.show', compact('plugin'));
    }

    public function edit(Plugin $plugin)
    {
        return view('plugins.edit', [
            'plugin' => $plugin,
            'availableHooks' => $this->getAvailableHooks()
        ]);
    }

    public function update(Request $request, Plugin $plugin)
    {
        try {
            $validated = $this->validatePluginData($request, $plugin);
            $this->pluginService->updatePlugin($plugin, $validated);
            return redirect()->route('plugin.index')->with('success', 'El plugin ha sido actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('La actualización del plugin ha fallado: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Plugin $plugin)
    {
        try {
            $this->pluginService->deletePlugin($plugin);
            return redirect()->route('plugin.index')->with('success', 'El plugin ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('La eliminación del plugin ha fallado: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function preview(Plugin $plugin, $viewName)
    {
        try {
            // Renderiza directamente la vista del plugin
            return view('plugins.preview', [
                'plugin' => $plugin,
                'viewName' => $viewName,
                'content' => $plugin->renderViewForEditor($viewName)
            ]);
        } catch (\Exception $e) {
            Log::error('Error rendering plugin view: ' . $e->getMessage());
            return back()->with('error', 'Error al renderizar la vista del plugin: ' . $e->getMessage());
        }
    }

    public function activate(Plugin $plugin)
    {
        try {
            $this->pluginService->activatePlugin($plugin);
            return redirect()->route('plugin.index')->with('success', 'El plugin ha sido activado correctamente');
        } catch (\Exception $e) {
            Log::error('La activación del plugin ha fallado: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function deactivate(Plugin $plugin)
    {
        try {
            $this->pluginService->deactivatePlugin($plugin);
            return redirect()->route('plugin.index')->with('success', 'El plugin ha sido desactivado correctamente');
        } catch (\Exception $e) {
            Log::error('La desactivación del plugin ha fallado: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function configure(Plugin $plugin)
    {
        if (!Gate::allows('configure', $plugin)) {
            abort(403);
        }

        return view('plugins.configure', [
            'plugin' => $plugin,
            'fileSystem' => $this->getPluginFileSystem($plugin)
        ]);
    }

    public function serveAsset(Plugin $plugin, $path)
    {
        $fullPath = base_path("plugins/{$plugin->name}/assets/{$path}");

        if (!File::exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }

    protected function validatePluginData(Request $request, Plugin $plugin = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'main_class' => ['required', 'string', 'max:255'],
            'selected_hooks' => ['nullable', 'array'],
            'selected_hooks.*' => ['string'],
            'is_global' => ['boolean'],
            'config' => ['nullable', 'json'],
            'migrations' => ['nullable', 'string'],
            'views' => ['nullable', 'string'],
            'create_model' => ['nullable', 'boolean'],
            'model_name' => ['required_if:create_model,1', 'nullable', 'string'],
            'create_controller' => ['nullable', 'boolean'],
            'controller_name' => ['required_if:create_controller,1', 'nullable', 'string']
        ];

        if (!$plugin) {
            $rules['name'][] = 'unique:plugins,name';
        }

        $validated = $request->validate($rules);

        // Add default values
        $validated['version'] = '1.0.0';
        $validated['author'] = Auth::user()->name;

        return $validated;
    }

    protected function getAvailableHooks(): array
    {
        return [
            'before_header' => 'Executes before header rendering',
            'after_header' => 'Executes after header rendering',
            'before_content' => 'Executes before main content',
            'after_content' => 'Executes after main content',
            'before_footer' => 'Executes before footer rendering',
            'after_footer' => 'Executes after footer rendering',
            'head' => 'Executes in HTML head section',
            'footer' => 'Executes at end of HTML body'
        ];
    }

    private function getPluginFileSystem(Plugin $plugin): array
    {
        $pluginPath = base_path('plugins/' . $plugin->name);
        return $this->scanDirectory($pluginPath);
    }

    private function scanDirectory(string $path): array
    {
        $result = [];

        if (!File::exists($path)) {
            return $result;
        }

        $files = File::files($path);
        $directories = File::directories($path);

        foreach ($directories as $directory) {
            $result[] = [
                'id' => str_replace('\\', '/', str_replace(base_path(), '', $directory)),
                'text' => basename($directory),
                'type' => 'folder',
                'children' => $this->scanDirectory($directory)
            ];
        }

        foreach ($files as $file) {
            $result[] = [
                'id' => str_replace('\\', '/', str_replace(base_path(), '', $file->getPathname())),
                'text' => $file->getFilename(),
                'type' => 'file'
            ];
        }

        return $result;
    }
}
