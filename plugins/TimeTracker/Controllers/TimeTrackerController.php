<?php

namespace Plugins\TimeTracker\Controllers;

use App\Http\Controllers\Controller;
use Plugins\TimeTracker\Models\TimeRecord;
use Illuminate\Http\Request;

class TimeTrackerController extends Controller
{
    public function index()
    {
        return [
            'records' => TimeRecord::orderBy('record_time', 'desc')->get()
        ];
    }

    public function store(Request $request)
    {
        TimeRecord::create([
            'record_time' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Tiempo registrado correctamente');
    }
}
