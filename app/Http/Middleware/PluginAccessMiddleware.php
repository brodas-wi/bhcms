<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PluginAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Plugin Access Middleware', [
            'user' => auth()->id(),
            'path' => $request->path(),
            'authenticated' => auth()->check()
        ]);

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }

    private function getActionFromRequest(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        if (str_contains($path, '/activate'))
            return 'activate';
        if (str_contains($path, '/deactivate'))
            return 'deactivate';
        if (str_contains($path, '/configure'))
            return 'configure';

        switch ($method) {
            case 'GET':
                return 'view';
            case 'POST':
                return 'create';
            case 'PUT':
                return 'update';
            case 'DELETE':
                return 'delete';
            default:
                return 'view';
        }
    }
}
