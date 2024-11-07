<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\HtmlFormatterService;

class FooterController extends Controller
{
    public function index()
    {
        $footers = Footer::latest()->get()->map(function ($footer) {
            $footer->content = html_entity_decode($footer->content);
            $footer->css = html_entity_decode($footer->css);
            return $footer;
        });
        return view('footers.index', compact('footers'));
    }

    public function create()
    {
        return view('footers.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'content' => 'required|string',
                'css' => 'nullable|string',
                'template_id' => 'nullable|exists:templates,id',
                'settings' => 'nullable|json'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Decodificar el contenido antes de formatearlo
            $content = html_entity_decode($request->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $htmlFormatter = app(HtmlFormatterService::class);
            // Formatear el contenido decodificado
            $formattedContent = $htmlFormatter->formatHtml($content, true);

            // Formatear CSS si existe
            $css = null;
            if ($request->css) {
                $decodedCss = html_entity_decode($request->css, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $css = $htmlFormatter->formatStyles($decodedCss);
            }

            // Codificar el contenido para almacenamiento
            $footer = Footer::create([
                'name' => $request->name,
                'content' => htmlspecialchars($formattedContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'css' => $css ? htmlspecialchars($css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
                'template_id' => $request->template_id,
                'settings' => $request->settings ? json_decode($request->settings, true) : null,
                'is_active' => true
            ]);

            // Decodificar el contenido para la respuesta
            $footer->content = html_entity_decode($footer->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $footer->css = $footer->css ? html_entity_decode($footer->css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;

            return response()->json([
                'success' => true,
                'message' => 'Footer created successfully',
                'data' => $footer
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating footer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating footer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $footer = Footer::findOrFail($id);
        return view('footers.edit', compact('footer'));
    }

    public function update(Request $request, Footer $footer)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'content' => 'required|string',
                'css' => 'nullable|string',
                'template_id' => 'nullable|exists:templates,id',
                'settings' => 'nullable|json'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Decodificar el contenido antes de formatearlo
            $content = html_entity_decode($request->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $htmlFormatter = app(HtmlFormatterService::class);
            // Formatear el contenido decodificado
            $formattedContent = $htmlFormatter->formatHtml($content, true);

            // Formatear CSS si existe
            $css = null;
            if ($request->css) {
                $decodedCss = html_entity_decode($request->css, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $css = $htmlFormatter->formatStyles($decodedCss);
            }

            // Actualizar con el contenido codificado
            $footer->update([
                'name' => $request->name,
                'content' => htmlspecialchars($formattedContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'css' => $css ? htmlspecialchars($css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
                'template_id' => $request->template_id,
                'settings' => $request->settings ? json_decode($request->settings, true) : null
            ]);

            // Decodificar el contenido para la respuesta
            $footer->refresh();
            $footer->content = html_entity_decode($footer->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $footer->css = $footer->css ? html_entity_decode($footer->css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;

            return response()->json([
                'success' => true,
                'message' => 'Footer updated successfully',
                'data' => $footer
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating footer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating footer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Footer $footer)
    {
        $footer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Footer deleted successfully'
        ]);
    }

    public function toggleActive(Footer $footer)
    {
        try {
            $currentState = $footer->is_active;
            $footer->is_active = !$currentState;
            $footer->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'is_active' => $footer->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error en toggleActive: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}
