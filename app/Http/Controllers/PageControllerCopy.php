<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Category;
use App\Models\Template;
use App\Models\Plugin;
use App\Models\NavigationItem;
use App\Services\HookSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PageControllerCopy extends Controller
{

    /**
     * Contructor for display pages.
     */
    public function __construct()
    {
        $navigation = NavigationItem::whereNull('parent_id')->with('children')->orderBy('order')->get();
        view()->share('navigation', $navigation);
    }

    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $pages = Page::all();
        return view('pages.index', compact('pages'));
    }

    public function getVersion(Request $request, Page $page)
    {
        $version = $request->query('version');

        if ($version == $page->version) {
            // Si es la versión actual, devolvemos los datos de la página actual
            $pageData = $page;
        } else {
            // Si es una versión anterior, buscamos en la tabla de versiones
            $pageVersion = $page->versions()->where('version', $version)->firstOrFail();

            // Creamos un objeto Page con los datos de la versión
            $pageData = new Page([
                'name' => $page->name,
                'description' => $page->description,
                'slug' => $page->slug,
                'content' => $pageVersion->content,
                'serialized_content' => $pageVersion->serialized_content,
                'template_id' => $page->template_id,
                'user_id' => $pageVersion->created_by,
                'status' => $page->status,
                'thumbnail' => $page->thumbnail,
                'version' => $pageVersion->version,
                'created_at' => $pageVersion->created_at,
                'updated_at' => $pageVersion->updated_at
            ]);
            $pageData->id = $page->id; // Mantenemos el ID original de la página
        }

        return response()->json([
            'success' => true,
            'page' => $pageData->load('user', 'template')
        ]);
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        $defaultContent = $this->getDefaultTemplateContent();
        $defaultStyles = $this->getDefaultTemplateStyles();

        $page = new Page();
        $page->content = $defaultContent;

        // Get all available plugins
        $availablePlugins = Plugin::where('is_active', true)->get()->map(function ($plugin) {
            return [
                'id' => $plugin->id,
                'original_name' => $plugin->original_name,
                'name' => $plugin->name, // Este es el identificador
                'description' => $plugin->description,
                'views' => $plugin->views, // Asumiendo que tienes un campo 'views' en tu modelo Plugin
            ];
        });

        return view('pages.editor', compact('page', 'defaultStyles', 'availablePlugins'));
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        // \Log::info('Received request', $request->all());

        try {
            // Validar los datos recibidos
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'required|string',
                'serialized_content' => 'required|json',
                'template_id' => 'required|exists:templates,id',
                'status' => 'required|in:draft,published',
            ]);

            // \Log::info('Validation passed', $validatedData);

            // Crear una nueva página
            $page = new Page($validatedData);
            $page->user_id = auth()->id();

            if ($validatedData['status'] == 'published') {
                $page->date_published = now();
            }

            $page->active_plugins = $request->input('active_plugins', []);

            $page->save();

            // \Log::info('Page saved', ['page_id' => $page->id]);

            return response()->json([
                'success' => true,
                'message' => 'Página creada exitosamente',
                'page' => $page
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error saving page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la página: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified page.
     */
    public function show(Page $page)
    {
        $page->load('user');
        $categories = Category::all();
        $templates = Template::all();

        return view('pages.show', compact('page', 'categories', 'templates'));
    }

    /**
     * Display the specified parent page.
     */
    public function display($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $navigation = NavigationItem::whereNull('parent_id')->with('children')->orderBy('order')->get();

        $hookSystem = new HookSystem();

        $pluginOutputs = [];

        // Comprueba si active_plugins no es null
        if ($page->active_plugins !== null) {
            foreach ($page->active_plugins as $pluginId) {
                $plugin = Plugin::find($pluginId);
                if ($plugin && $plugin->is_active) {
                    $this->loadPlugin($plugin, $hookSystem);

                    // Ejecutar el método display del plugin si existe
                    $mainClass = $plugin->main_class;
                    if (class_exists($mainClass)) {
                        $instance = new $mainClass();
                        if (method_exists($instance, 'display')) {
                            $pluginOutputs[$plugin->slug] = $instance->display($page->id);
                        }
                    }
                }
            }
        }

        $hookSystem->doAction('before_page_render', $page);

        $content = $hookSystem->applyFilters('page_content', $page->content, $page);

        $hookSystem->doAction('after_page_content', $content, $page);

        return view('pages.display', compact('page', 'navigation', 'content', 'pluginOutputs'));
    }

    /**
     * Load and register active plugins
     */
    private function loadPlugin(Plugin $plugin, HookSystem $hookSystem)
    {
        $mainClass = $plugin->main_class;
        if (class_exists($mainClass)) {
            $instance = new $mainClass();
            if (method_exists($instance, 'register')) {
                $instance->register($hookSystem);
            }
            if (method_exists($instance, 'boot')) {
                $instance->boot();
            }
        } else {
            \Log::warning("No se pudo cargar la clase principal del plugin: {$plugin->name}");
        }
    }

    public function getActivePlugins(Page $page)
    {
        $activePlugins = [];
        if ($page->active_plugins !== null) {
            foreach ($page->active_plugins as $pluginId) {
                $plugin = Plugin::find($pluginId);
                if ($plugin && $plugin->is_active) {
                    $activePlugins[] = [
                        'id' => $plugin->id,
                        'name' => $plugin->name,
                        'description' => $plugin->description,
                        'icon' => $plugin->icon ?? 'fa fa-puzzle-piece'
                    ];
                }
            }
        }
        return $activePlugins;
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        $user = Auth::user();

        if ($page->template_id) {
            $template = Template::find($page->template_id);
            $defaultStyles = $template ? $template->styles : '';
        } else {
            $defaultStyles = $this->getDefaultTemplateStyles();
        }

        // Get all available plugins
        $availablePlugins = Plugin::where('is_active', true)->get()->map(function ($plugin) {
            return [
                'id' => $plugin->id,
                'original_name' => $plugin->original_name,
                'name' => $plugin->name, // Este es el identificador
                'description' => $plugin->description,
                'views' => $plugin->views, // Asumiendo que tienes un campo 'views' en tu modelo Plugin
            ];
        });

        return view('pages.editor', compact('page', 'user', 'defaultStyles', 'availablePlugins'));
    }

    public function renderPluginView(Request $request)
    {
        $plugin = Plugin::where('name', $request->name)->firstOrFail();

        if (empty($plugin->views)) {
            \Log::warning("No views found for plugin: {$plugin->name}");
            abort(404, "No se encontraron vistas para este plugin.");
        }

        $viewName = $plugin->views[0];  // Obtener la primera vista del array

        \Log::info("Rendering view for plugin: {$plugin->name}, View: {$viewName}");

        if (!in_array($viewName, $plugin->views)) {
            \Log::warning("Attempted to access non-existent view: {$viewName} for plugin: {$plugin->name}");
            abort(404, "Vista no encontrada en este plugin.");
        }

        try {
            $view = $plugin->renderView($viewName);

            // Verificar si la vista tiene las variables necesarias
            $viewData = $view->getData();
            \Log::info("View data:", $viewData);

            return $view;
        } catch (\Exception $e) {
            \Log::error("Error rendering view: " . $e->getMessage());
            abort(500, "Error al renderizar la vista del plugin: " . $e->getMessage());
        }
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        // Validación base común para ambos casos
        $baseValidation = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'template_id' => 'required|exists:templates,id',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Validación específica para la actualización desde el editor
        $editorValidation = [
            'serialized_content' => 'required|json',
            'active_plugins' => 'nullable|array',
            'active_plugins.*' => 'exists:plugins,id',
            'version_type' => 'required|in:minor,major,current'
        ];

        // Determinar si la actualización viene de la vista show
        $isFromShowView = $request->input('update_source') === 'show_view';

        // Aplicar la validación correspondiente
        $validatedData = $request->validate($isFromShowView ? $baseValidation : array_merge($baseValidation, $editorValidation));

        $selectedVersion = $request->input('selected_version');
        $versionChanged = $selectedVersion !== $page->version;

        if ($isFromShowView) {
            if ($versionChanged) {
                // Verificamos si ya existe una versión guardada para la versión actual
                $existingVersion = PageVersion::where('page_id', $page->id)
                    ->where('version', $page->version)
                    ->first();

                // Solo guardamos la versión actual si no existe ya en page_versions
                if (!$existingVersion) {
                    PageVersion::create([
                        'page_id' => $page->id,
                        'content' => $page->content,
                        'serialized_content' => $page->serialized_content,
                        'version' => $page->version,
                        'created_by' => $page->user_id
                    ]);
                }

                // Cargamos la versión seleccionada
                $pageVersion = $page->versions()->where('version', $selectedVersion)->firstOrFail();

                // Actualizamos la página con los datos de la versión seleccionada
                $page->content = $pageVersion->content;
                $page->serialized_content = $pageVersion->serialized_content;
                $page->version = $selectedVersion;
            }
            // Si la versión no cambió, simplemente actualizamos con los datos del formulario
        } else {
            // Lógica de versionado para actualizaciones desde el editor
            $versionType = $validatedData['version_type'];

            if ($versionType !== 'current') {
                $this->saveCurrentVersionIfNotExists($page);
                $page->version = $this->getNewVersion($page->version, $versionType);
            }

            $page->serialized_content = $request->serialized_content;
            $page->active_plugins = $request->input('active_plugins', []);
        }

        $page->fill($validatedData);

        if (!$isFromShowView) {
            $page->serialized_content = $request->serialized_content;
            $page->active_plugins = $request->input('active_plugins', []);
        }

        if ($page->isDirty('status') && $validatedData['status'] == 'published') {
            $page->date_published = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($page->thumbnail) {
                Storage::delete(str_replace('/storage', 'public', $page->thumbnail));
            }
            $thumbnailPath = $request->file('thumbnail')->store('public/media');
            $page->thumbnail = Storage::url($thumbnailPath);
        }

        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Página actualizada exitosamente',
            'page' => $page
        ]);
    }

    private function saveCurrentVersionIfNotExists(Page $page)
    {
        $existingVersion = PageVersion::where('page_id', $page->id)
            ->where('version', $page->version)
            ->first();

        if (!$existingVersion) {
            PageVersion::create([
                'page_id' => $page->id,
                'content' => $page->content,
                'serialized_content' => $page->serialized_content,
                'version' => $page->version,
                'created_by' => $page->user_id
            ]);
        }
    }

    private function getNewVersion($currentVersion, $versionType)
    {
        $versionParts = explode('.', $currentVersion);

        if ($versionType === 'minor') {
            $versionParts[1]++;
            $versionParts[2] = 0;
        } elseif ($versionType === 'major') {
            $versionParts[0]++;
            $versionParts[1] = 0;
            $versionParts[2] = 0;
        }

        return implode('.', $versionParts);
    }

    private function incrementVersion($version)
    {
        $versionParts = explode('.', $version);
        $versionParts[count($versionParts) - 1]++;
        return implode('.', $versionParts);
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }

    private function getDefaultTemplateContent()
    {
        $path = resource_path('views/templates/default_template.blade.php');
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        \Log::warning('No se encontró el archivo de plantilla predeterminada');
        return '<div class="container"><h1>Nueva Plantilla</h1><p>Contenido de la plantilla...</p></div>';
    }

    private function getDefaultTemplateStyles()
    {
        $path = resource_path('views/templates/assets/default_styles.css');
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        \Log::warning('No se encontró el archivo de estilos CSS personalizados');
        return 'body { color: black; }';
    }
}
