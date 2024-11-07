<?php

namespace App\Http\Controllers;

use App\Models\Navbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\HtmlFormatterService;

class NavbarController extends Controller
{
    public function index()
    {
        $navbars = Navbar::latest()->get()->map(function ($navbar) {
            $navbar->content = html_entity_decode($navbar->content);
            $navbar->css = html_entity_decode($navbar->css);
            return $navbar;
        });
        return view('navbars.index', compact('navbars'));
    }

    public function create()
    {
        return view('navbars.create');
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
            $navbar = Navbar::create([
                'name' => $request->name,
                'content' => htmlspecialchars($formattedContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'css' => $css ? htmlspecialchars($css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
                'template_id' => $request->template_id,
                'settings' => $request->settings ? json_decode($request->settings, true) : null,
                'is_active' => true
            ]);

            // Decodificar el contenido para la respuesta
            $navbar->content = html_entity_decode($navbar->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $navbar->css = $navbar->css ? html_entity_decode($navbar->css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;

            return response()->json([
                'success' => true,
                'message' => 'Navbar created successfully',
                'data' => $navbar
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating navbar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating navbar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $navbar = Navbar::findOrFail($id);
        return view('navbars.edit', compact('navbar'));
    }

    public function update(Request $request, Navbar $navbar)
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
            $navbar->update([
                'name' => $request->name,
                'content' => htmlspecialchars($formattedContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'css' => $css ? htmlspecialchars($css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
                'template_id' => $request->template_id,
                'settings' => $request->settings ? json_decode($request->settings, true) : null
            ]);

            // Decodificar el contenido para la respuesta
            $navbar->refresh();
            $navbar->content = html_entity_decode($navbar->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $navbar->css = $navbar->css ? html_entity_decode($navbar->css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;

            return response()->json([
                'success' => true,
                'message' => 'Navbar updated successfully',
                'data' => $navbar
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating navbar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating navbar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Navbar $navbar)
    {
        $navbar->delete();
        return response()->json([
            'success' => true,
            'message' => 'Navbar deleted successfully'
        ]);
    }

    // GrapesJS specific endpoints
    public function load(Request $request)
    {
        try {
            $navbar = Navbar::firstOrNew();

            return response()->json([
                'components' => $navbar->content ?? '',
                'styles' => $navbar->css ?? '',
                'settings' => $navbar->settings ?? [],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'components' => 'required|string',
            'style' => 'nullable|string',
            'settings' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Decodificar y formatear el contenido
        $content = html_entity_decode($request->components, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $htmlFormatter = app(HtmlFormatterService::class);
        $formattedContent = $htmlFormatter->formatHtml($content, true);

        // Formatear CSS si existe
        $css = null;
        if ($request->style) {
            $decodedCss = html_entity_decode($request->style, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $css = $htmlFormatter->formatStyles($decodedCss);
        }

        $navbar = Navbar::findOrCreate($request->id ?? null, [
            'name' => $request->name ?? 'New Navbar',
            'content' => htmlspecialchars($formattedContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'css' => $css ? htmlspecialchars($css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
            'settings' => $request->settings
        ]);

        // Decodificar para la respuesta
        $navbar->content = html_entity_decode($navbar->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $navbar->css = $navbar->css ? html_entity_decode($navbar->css, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;

        return response()->json([
            'success' => true,
            'message' => 'State saved successfully',
            'data' => $navbar
        ]);
    }

    public function toggleActive(Navbar $navbar)
    {
        Log::info('Toggle Active llamado para navbar: ' . $navbar->id);

        try {
            $currentState = $navbar->is_active;
            $navbar->is_active = !$currentState;
            $navbar->save();

            Log::info('Estado actualizado correctamente', [
                'navbar_id' => $navbar->id,
                'old_state' => $currentState,
                'new_state' => $navbar->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'is_active' => $navbar->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error en toggleActive: ' . $e->getMessage(), [
                'navbar_id' => $navbar->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}
