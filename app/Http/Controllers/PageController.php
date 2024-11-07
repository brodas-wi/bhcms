<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Navbar;
use App\Models\Footer;
use App\Models\Category;
use App\Models\Template;
use App\Models\Plugin;
use App\Models\NavigationItem;
use App\Services\HookSystem;
use App\Services\HtmlFormatterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth, Storage, Log, File};
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    protected HtmlFormatterService $htmlFormatter;

    /**
     * Constructor for PageController
     *
     * @param HtmlFormatterService $htmlFormatter
     */
    public function __construct(HtmlFormatterService $htmlFormatter)
    {
        $this->htmlFormatter = $htmlFormatter;
        $navigation = NavigationItem::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();
        view()->share('navigation', $navigation);
    }

    /**
     * Display a listing of pages with optional filters
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Page::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $pages = $query->get();
        $categories = Category::all();

        return view('pages.index', compact('pages', 'categories'));
    }

    /**
     * Get a specific version of a page
     *
     * @param Request $request
     * @param Page $page
     * @return JsonResponse
     */
    public function getVersion(Request $request, Page $page): JsonResponse
    {
        $version = $request->query('version');

        if ($version == $page->version) {
            $pageData = $page;
        } else {
            $pageVersion = $page->versions()->where('version', $version)->firstOrFail();
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
            $pageData->id = $page->id;
        }

        return response()->json([
            'success' => true,
            'page' => $pageData->load('user', 'template')
        ]);
    }

    /**
     * Show the form for creating a new page
     *
     * @return View
     */
    public function create(): View
    {
        $page = new Page();
        $page->content = $this->getDefaultTemplateContent();
        $defaultStyles = $this->getDefaultTemplateStyles();
        $availablePlugins = $this->getAvailablePlugins();

        // Inicializar valores por defecto para nueva página
        $serializedContent = null;
        $activePlugins = [];

        return view('pages.editor', compact(
            'page',
            'defaultStyles',
            'availablePlugins',
            'serializedContent',
            'activePlugins'
        ));
    }

    /**
     * Store a newly created page
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'content' => 'required|string',
                'serialized_content' => 'required|json',
                'template_id' => 'required|exists:templates,id',
                'status' => 'required|in:draft,published',
            ]);

            $validatedData['content'] = $this->prepareContent($validatedData['content']);
            $page = new Page($validatedData);
            $page->user_id = auth()->id();
            $page->date_published = $validatedData['status'] === 'published' ? now() : null;
            $page->active_plugins = $request->input('active_plugins', []);

            $page->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully',
                'page' => $page
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified page
     *
     * @param Page $page
     * @return View
     */
    public function show(Page $page): View
    {
        $page->load('user', 'category', 'versions');
        $categories = Category::all();
        $templates = Template::all();
        $navbars = Navbar::all();
        $footers = Footer::all();

        return view('pages.show', compact(
            'page',
            'categories',
            'templates',
            'navbars',
            'footers'
        ));
    }

    /**
     * Display the public view of a page
     *
     * @param string $slug
     * @return View
     */
    public function display(string $slug): View
    {
        try {
            $page = Page::where('slug', $slug)
                ->with([
                    'navbar' => function ($query) {
                        $query->where('is_active', true);
                    },
                    'footer' => function ($query) {
                        $query->where('is_active', true);
                    },
                    'template'
                ])
                ->firstOrFail();

            // Procesar contenido del navbar
            if ($page->navbar) {
                $page->navbar->content = html_entity_decode($page->navbar->content);
                $page->navbar->css = $page->navbar->css ? html_entity_decode($page->navbar->css) : null;
            }

            // Procesar contenido del footer
            if ($page->footer) {
                $page->footer->content = html_entity_decode($page->footer->content);
                $page->footer->css = $page->footer->css ? html_entity_decode($page->footer->css) : null;
            }

            // Obtener plugins globales
            $globalPlugins = Plugin::where('is_global', true)
                ->where('is_active', true)
                ->get();

            // Obtener plugins de la página
            $pagePlugins = $page->active_plugins ?
                Plugin::whereIn('id', $page->active_plugins)
                ->where('is_active', true)
                ->get() :
                collect([]);

            $pluginResources = $this->collectPluginResources($globalPlugins, $pagePlugins);
            $hookSystem = app(HookSystem::class);
            $content = $page->content;

            return view('pages.display', [
                'page' => $page,
                'content' => $content,
                'pluginStyles' => $pluginResources['styles'],
                'pluginScripts' => $pluginResources['scripts'],
                'hookSystem' => $hookSystem
            ]);
        } catch (\Exception $e) {
            Log::error('Error displaying page', [
                'slug' => $slug,
                'error' => $e->getMessage()
            ]);

            abort(404, 'Page not found');
        }
    }

    /**
     * Show the form for editing the specified page
     *
     * @param Page $page
     * @return View
     */
    public function edit(Page $page): View
    {
        $user = Auth::user();
        $defaultStyles = $page->template_id ?
            Template::find($page->template_id)?->styles :
            $this->getDefaultTemplateStyles();

        $availablePlugins = $this->getAvailablePlugins();
        $activePlugins = $page->active_plugins ?? [];
        $serializedContent = $page->serialized_content;
        $navbars = Navbar::all();
        $footers = Footer::all();

        return view('pages.editor', compact(
            'page',
            'user',
            'defaultStyles',
            'availablePlugins',
            'navbars',
            'footers',
            'serializedContent',
            'activePlugins'
        ));
    }

    /**
     * Update the specified page
     *
     * @param Request $request
     * @param Page $page
     * @return JsonResponse
     */
    public function update(Request $request, Page $page): JsonResponse
    {
        try {
            DB::beginTransaction();

            $isFromShowView = $request->input('update_source') === 'show_view';
            $validatedData = $this->validateUpdateRequest($request, $isFromShowView);

            if (isset($validatedData['content'])) {
                $validatedData['content'] = $this->prepareContent($validatedData['content']);
            }

            if ($request->hasFile('thumbnail')) {
                $validatedData['thumbnail'] = $this->handleThumbnailUpload($request, $page);
            }

            if ($validatedData['status'] === 'published' && $page->status !== 'published') {
                $validatedData['date_published'] = now();
            }

            $this->handleVersioning($page, $request, $validatedData);
            $page->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully',
                'page' => $page->fresh()->load('user', 'template')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating page', [
                'page_id' => $page->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate the update request data
     *
     * @param Request $request
     * @param bool $isFromShowView
     * @return array
     */
    protected function validateUpdateRequest(Request $request, bool $isFromShowView): array
    {
        if ($isFromShowView) {
            return $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'template_id' => 'required|exists:templates,id',
                'status' => 'required|in:draft,published',
                'navbar_id' => 'nullable|exists:navbars,id',
                'footer_id' => 'nullable|exists:footers,id',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required|string',
            'serialized_content' => 'required|json',
            'template_id' => 'required|exists:templates,id',
            'status' => 'required|in:draft,published',
            'active_plugins' => 'nullable|array',
            'plugin_data' => 'nullable|array',
            'navbar_id' => 'nullable|exists:navbars,id',
            'footer_id' => 'nullable|exists:footers,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    }

    /**
     * Handle thumbnail upload
     *
     * @param Request $request
     * @param Page $page
     * @return string
     */
    protected function handleThumbnailUpload(Request $request, Page $page): string
    {
        if ($page->thumbnail) {
            Storage::disk('public')->delete($page->thumbnail);
        }

        return $request->file('thumbnail')->store('thumbnails', 'public');
    }

    /**
     * Handle page versioning
     *
     * @param Page $page
     * @param Request $request
     * @param array $validatedData
     * @return void
     */
    protected function handleVersioning(Page $page, Request $request, array $validatedData): void
    {
        // Create a new version if content has changed
        if (isset($validatedData['content']) && $page->content !== $validatedData['content']) {
            $newVersion = $page->getNextMinorVersion();

            PageVersion::create([
                'page_id' => $page->id,
                'content' => $page->content,
                'serialized_content' => $page->serialized_content,
                'version' => $page->version,
                'created_by' => $page->user_id
            ]);

            $validatedData['version'] = $newVersion;
        }

        $page->fill($validatedData);
    }

    /**
     * Remove the specified page
     *
     * @param Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    // Protected helper methods

    /**
     * Get available plugins for the page editor
     */
    protected function getAvailablePlugins(): array
    {
        return Plugin::where('is_active', true)
            ->where('is_global', false)
            ->get()
            ->map(function ($plugin) {
                return [
                    'id' => $plugin->id,
                    'name' => $plugin->name,
                    'original_name' => $plugin->original_name,
                    'views' => $plugin->views ?? ['index'],
                    'description' => $plugin->description,
                    'icon' => $plugin->icon ?? 'fa-puzzle-piece'
                ];
            })
            ->toArray();
    }

    /**
     * Collect and merge plugin resources (styles and scripts)
     */
    protected function collectPluginResources($globalPlugins, $pagePlugins): array
    {
        $styles = '';
        $scripts = '';

        foreach ([$globalPlugins, $pagePlugins] as $plugins) {
            foreach ($plugins as $plugin) {
                $styles .= $plugin->getStyles() . "\n";
                $scripts .= $plugin->getScripts() . "\n";
            }
        }

        return [
            'styles' => $styles,
            'scripts' => $scripts
        ];
    }

    /**
     * Upload an image for the page editor
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided'
                ], 400);
            }

            // Obtener el archivo
            $file = $request->file('image');

            // Crear un nombre único para el archivo
            $filename = uniqid() . '_' . $file->getClientOriginalName();

            // Mover el archivo al directorio public/images directamente
            $file->move(public_path('images'), $filename);

            // Generar la URL usando asset() helper
            $url = asset('images/' . $filename);

            return response()->json([
                'success' => true,
                'url' => $url,
                'name' => $filename
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading image:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all images from storage
     *
     * @return JsonResponse
     */
    public function getImages(): JsonResponse
    {
        try {
            // Obtener archivos directamente del directorio public/images
            $files = File::files(public_path('images'));

            $images = [];
            foreach ($files as $file) {
                if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $images[] = [
                        'url' => asset('images/' . $file->getFilename()),
                        'name' => $file->getFilename()
                    ];
                }
            }

            return response()->json($images);
        } catch (\Exception $e) {
            Log::error('Error getting images:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check storage configuration
     *
     * @return JsonResponse
     */
    public function checkStorageConfig(): JsonResponse
    {
        $config = [
            'app_url' => config('app.url'),
            'filesystem_driver' => config('filesystems.default'),
            'public_disk_config' => config('filesystems.disks.public'),
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage'),
            'symlink_exists' => file_exists(public_path('storage')),
            'images_dir_exists' => Storage::disk('public')->exists('images'),
            'storage_permissions' => decoct(fileperms(storage_path('app/public')) & 0777),
            'public_link_permissions' => file_exists(public_path('storage')) ? decoct(fileperms(public_path('storage')) & 0777) : 'N/A'
        ];

        return response()->json($config);
    }

    /**
     * Get default template content
     */
    protected function getDefaultTemplateContent(): string
    {
        $path = resource_path('views/templates/default_template.blade.php');
        return file_exists($path)
            ? file_get_contents($path)
            : '<div class="container"><h1>New Template</h1><p>Template content...</p></div>';
    }

    /**
     * Get default template styles
     */
    protected function getDefaultTemplateStyles(): string
    {
        $path = resource_path('views/templates/assets/default_styles.css');
        return file_exists($path)
            ? file_get_contents($path)
            : 'body { color: black; }';
    }

    /**
     * Prepare HTML content
     */
    protected function prepareContent(string $content): string
    {
        return $this->htmlFormatter->formatHtml($content, true);
    }
}
