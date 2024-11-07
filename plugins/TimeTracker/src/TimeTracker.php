<?php

namespace Plugins\TimeTracker\src;

use App\Plugins\BasePlugin;
use App\Services\HookSystem;
use Illuminate\Support\Facades\Route;
use Plugins\TimeTracker\Controllers\TimeTrackerController;
use Plugins\TimeTracker\Models\TimeRecord;

class TimeTracker extends BasePlugin
{
    public function register(HookSystem $hookSystem): void
    {
        $hookSystem->addAction('after_content', [$this, 'render']);
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web'])->group(function () {
            // Modified route to return JSON response
            Route::get('/plugins/' . $this->getPluginName() . '/preview/{view}', function ($view) {
                try {
                    $controller = app()->make(TimeTrackerController::class);
                    $data = method_exists($controller, $view) ?
                        $controller->{$view}() :
                        $controller->index();

                    $html = view($this->getPluginName() . '::' . $view, $data)->render();

                    return response()->json([
                        'success' => true,
                        'html' => $html,
                        'data' => $data
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error' => $e->getMessage()
                    ], 500);
                }
            });

            Route::post('/plugins/time-tracker/record', [TimeTrackerController::class, 'store'])
                ->name('time-records.store');
        });
    }

    protected function getPluginName()
    {
        return class_basename(get_class($this));
    }

    public function render(): string
    {
        $controller = app()->make(TimeTrackerController::class);
        return view('TimeTracker::index', $controller->index())->render();
    }
}
