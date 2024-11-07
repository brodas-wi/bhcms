<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = Media::all();
        return view('media.index', compact('media'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'type' => $request->type,
            'url' => Storage::url($path),
            'title' => $request->title,
            'description' => $request->description,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json($media, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        return view('media.show', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
        ]);

        $media->update($request->only(['title', 'description']));

        return response()->json($media);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        Storage::disk('public')->delete(str_replace('/storage/', '', $media->url));
        $media->delete();

        return response()->json(null, 204);
    }

    /**
     * Create thumbnail from base64 image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createThumbnail(Request $request)
    {
        Log::info('Iniciando creación de thumbnail', $request->all());

        $request->validate([
            'image' => 'required|string',
            'templateId' => 'required|exists:templates,id',
            'templateName' => 'required|string',
        ]);

        try {
            // Decodificar la imagen
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));

            // Generar un nombre único para el archivo
            $filename = 'thumbnail_' . Str::slug($request->templateName) . '_' . time() . '.png';
            $path = 'thumbnails/' . $filename;

            Log::info('Intentando guardar la imagen', ['path' => $path]);

            // Guardar la imagen en el almacenamiento público
            $saved = Storage::disk('public')->put($path, $imageData);

            if (!$saved) {
                Log::error('No se pudo guardar la imagen en el disco');
                return response()->json(['success' => false, 'message' => 'Failed to save image'], 500);
            }

            Log::info('Imagen guardada exitosamente');

            // Crear registro en la tabla media
            $media = Media::create([
                'type' => 'thumbnail',
                'url' => Storage::disk('public')->url($path),
                'title' => 'Thumbnail for ' . $request->templateName,
                'description' => 'Automatically generated thumbnail for template: ' . $request->templateName,
                'filename' => $filename,
                'mime_type' => 'image/png',
                'size' => Storage::disk('public')->size($path),
            ]);

            Log::info('Registro de media creado', ['media_id' => $media->id]);

            // Actualizar el template con el nuevo media_id
            $template = Template::find($request->templateId);
            $template->media_id = $media->id;
            $template->save();

            Log::info('Template actualizado con nuevo media_id', ['template_id' => $template->id, 'media_id' => $media->id]);

            return response()->json([
                'success' => true,
                'message' => 'Thumbnail created successfully',
                'media' => $media
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear thumbnail: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating thumbnail: ' . $e->getMessage()
            ], 500);
        }
    }
}
