<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AjaxPluginController extends Controller
{
    public function handleAction(Request $request, string $pluginName, string $action): JsonResponse
    {
        $plugin = Plugin::where('name', $pluginName)->firstOrFail();
        $instance = $plugin->getInstance();

        if (method_exists($instance, $action)) {
            return $instance->$action();
        }

        return response()->json(['error' => 'Action not found'], 404);
    }
}
