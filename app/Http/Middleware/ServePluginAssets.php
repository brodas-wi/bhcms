<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ServePluginAssets
{
    public function handle(Request $request, Closure $next)
    {
        \Log::emergency('ServePluginAssets middleware', [
            'path' => $request->path(),
            'is_plugin_path' => strpos($request->path(), 'plugins/') === 0
        ]);
        $path = $request->path();
        if (strpos($path, 'plugins/') === 0) {
            $filePath = base_path($path);
            if (File::exists($filePath)) {
                return response()->file($filePath);
            }
        }

        return $next($request);
    }
}
