<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Category;
use App\Models\ContentVersion;
use App\Models\Tag;
use App\Services\ContentService;
use App\Http\Requests\ContentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Display a listing of the contents.
     */
    public function index(Request $request)
    {
        $query = Content::with(['categories', 'tags', 'user']);

        // Filtros
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $contents = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('contents.index', compact('contents', 'categories'));
    }

    /**
     * Show the form for creating a new content.
     */
    public function create()
    {
        $categories = Category::getSelectList();
        $tags = Tag::all();

        return view('contents.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(ContentRequest $request)
    {
        try {
            $content = $this->contentService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Contenido creado exitosamente',
                'redirect' => route('contents.index')
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating content: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el contenido: ' . $e->getMessage()
            ], 422);
        }
    }

    public function prueba()
    {
        return view('contents.prueba');
    }

    /**
     * Display the specified content.
     */
    public function show(Content $content)
    {
        $content->load(['categories', 'tags', 'user']);
        return view('contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Content $content)
    {
        $categories = Category::getSelectList();
        $tags = Tag::all();
        $versions = $content->versions()->latest()->get();

        // Preparar datos existentes para el frontend
        $existingData = [
            'categories' => $content->categories->pluck('id')->toArray(),
            'tags' => $content->tags->pluck('id')->toArray()
        ];

        return view('contents.edit', compact('content', 'categories', 'tags', 'versions', 'existingData'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(ContentRequest $request, Content $content)
    {
        try {
            $content = $this->contentService->update($content, $request->validated());

            return redirect()
                ->route('contents.index')
                ->with('success', 'Contenido actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el contenido: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Content $content)
    {
        try {
            $content->delete();
            return redirect()
                ->route('contents.index')
                ->with('success', 'Contenido movido a la papelera');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el contenido: ' . $e->getMessage());
        }
    }

    /**
     * Display trashed contents.
     */
    public function trash()
    {
        $trashedContents = Content::onlyTrashed()
            ->with(['categories', 'tags'])
            ->latest('deleted_at')
            ->paginate(10);

        return view('contents.trash', compact('trashedContents'));
    }

    /**
     * Restore the specified content from trash.
     */
    public function restore($id)
    {
        try {
            $content = Content::onlyTrashed()->findOrFail($id);
            $content->restore();

            return redirect()
                ->route('contents.trash')
                ->with('success', 'Contenido restaurado exitosamente');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al restaurar el contenido: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified content from trash.
     */
    public function forceDelete($id)
    {
        try {
            $content = Content::onlyTrashed()->findOrFail($id);

            // Eliminar versiones
            $content->versions()->delete();

            // Eliminar relaciones
            $content->categories()->detach();
            $content->tags()->detach();

            // Eliminar permanentemente
            $content->forceDelete();

            return redirect()
                ->route('contents.trash')
                ->with('success', 'Contenido eliminado permanentemente');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar permanentemente el contenido: ' . $e->getMessage());
        }
    }

    /**
     * Restore a specific version of the content.
     */
    public function restoreVersion(Content $content, ContentVersion $version)
    {
        try {
            // Crear versión del contenido actual antes de restaurar
            $content->createVersion();

            // Restaurar contenido de la versión seleccionada
            $content->update(['content' => $version->content]);

            return redirect()
                ->route('contents.edit', $content)
                ->with('success', 'Versión restaurada exitosamente');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al restaurar la versión: ' . $e->getMessage());
        }
    }

    /**
     * Handle image upload from the editor.
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|max:2048' // 2MB máximo
            ]);

            $path = $request->file('file')->store('content-images', 'public');

            return response()->json([
                'success' => true,
                'location' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function autosave(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 'draft';

            if (!empty($request->id)) {
                $content = Content::findOrFail($request->id);
                $this->contentService->update($content, $data);
            } else {
                $this->contentService->create($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contenido guardado automáticamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error autosaving content: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar automáticamente: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Preview content before publishing.
     */
    public function preview(Request $request)
    {
        $content = new Content($request->all());
        return view('contents.preview', compact('content'));
    }
}
