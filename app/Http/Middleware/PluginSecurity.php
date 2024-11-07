<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PluginSecurity
{
    public function handle(Request $request, Closure $next)
    {
        $plugin = $request->route('plugin');
        $filePath = $request->input('file_path');

        // Si no hay file_path, permitimos la solicitud (para la vista de configuración)
        if (!$filePath) {
            return $next($request);
        }

        if (!$this->isPathWithinPlugin($plugin, $filePath)) {
            return response()->json(['error' => 'Invalid file path'], 403);
        }

        return $next($request);
    }

    private function isPathWithinPlugin($plugin, $filePath)
    {
        $pluginPath = base_path('plugins/' . $plugin->name);
        $realPath = realpath($pluginPath . '/' . $filePath);
        return $realPath !== false && strpos($realPath, $pluginPath) === 0;
    }
}
