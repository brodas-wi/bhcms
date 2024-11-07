<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Template;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::query();
        $categories = Category::all();
        $pages = Page::all();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('layout')) {
            $query->where('layout', $request->layout);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $templates = $query->paginate(15);

        // return view('admin.templates.index', compact('templates'));
        return view('templates.index', compact('templates', 'categories', 'pages'));
    }

    public function create()
    {
        $defaultContent = $this->getDefaultTemplateContent();
        $defaultStyles = $this->getDefaultTemplateStyles();
        $pages = Page::all(); // Add this line

        $template = new Template();
        $template->styles = $defaultStyles;

        return view('templates.editor', compact('template', 'defaultContent', 'pages'));
    }

    private function getDefaultTemplateContent($page = null)
    {
        if ($page) {
            return $page->content;
        }

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

    public function show(Template $template)
    {
        $template->load('user');
        $categories = Category::all();

        return view('templates.show', compact('template', 'categories'));
    }

    public function edit(Template $template, Request $request)
    {
        $user = Auth::user();
        $pages = Page::all();
        $selectedPage = $request->has('page_id') ? Page::findOrFail($request->page_id) : null;
        $defaultContent = $this->getDefaultTemplateContent($selectedPage);

        return view('templates.editor', compact('template', 'user', 'defaultContent', 'pages', 'selectedPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'styles' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'is_default' => 'boolean',
            'folder_id' => 'nullable|exists:folders,id',
            'thumbnail' => 'nullable|string',
        ]);

        $template = Template::create($validated);

        return response()->json(['success' => true, 'template' => $template]);
    }

    // public function update(Request $request, Template $template)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'styles' => 'required|string',
    //         'user_id' => 'required|exists:users,id',
    //         'is_default' => 'boolean',
    //         'folder_id' => 'nullable|exists:folders,id',
    //         'thumbnail' => 'nullable|string',
    //     ]);

    //     $template->update($validatedData);

    //     return response()->json(['success' => true]);
    // }

    public function update(Request $request, Template $template)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'styles' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'is_default' => 'boolean',
                'folder_id' => 'nullable|exists:folders,id',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            \DB::beginTransaction();

            // Actualizar todos los campos excepto 'styles'
            $template->fill(array_diff_key($validatedData, ['styles' => '']));

            // Actualizar 'styles' explícitamente
            $template->styles = $validatedData['styles'];

            // Log para depuración
            \Log::info('Nuevos estilos a guardar:', ['styles' => $validatedData['styles']]);

            // Manejar la carga del thumbnail
            if ($request->hasFile('thumbnail')) {
                // Eliminar el thumbnail anterior si existe
                if ($template->thumbnail) {
                    Storage::delete(str_replace('/storage', 'public', $template->thumbnail));
                }

                // Guardar el nuevo thumbnail
                $thumbnailPath = $request->file('thumbnail')->store('public/media');
                $template->thumbnail = Storage::url($thumbnailPath);
            }
            $template->save();

            \DB::commit();

            // Recargar el modelo desde la base de datos
            $template->refresh();

            // Log para verificar los estilos guardados
            \Log::info('Estilos guardados en la base de datos:', ['styles' => $template->styles]);

            return response()->json([
                'success' => true,
                'message' => 'Template actualizado exitosamente',
                'template' => $template
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al actualizar el template: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function customize(Request $request, Template $template)
    {
        $validated = $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'font_family' => 'required|string',
            'custom_css' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.show', $template)
            ->with('success', 'Plantilla actualizada correctamente.');
    }

    public function getCssStyles()
    {
        $styles = Template::select('id', 'name')
            ->whereNotNull('styles')
            ->where('styles', '!=', '')
            ->get();
        return response()->json($styles);
    }

    public function getCssStyle($id)
    {
        $template = Template::findOrFail($id);
        if (empty($template->styles)) {
            return response()->json(['error' => 'No styles found for this template'], 404);
        }
        return response()->json(['styles' => $template->styles]);
    }
}
