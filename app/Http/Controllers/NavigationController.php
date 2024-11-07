<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NavigationItem;

class NavigationController extends Controller
{
    public function index()
    {
        $items = NavigationItem::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('navigation.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'url' => 'required_without:page_id',
            'page_id' => 'required_without:url|exists:pages,id',
            'parent_id' => 'nullable|exists:navigation_items,id',
            'order' => 'integer',
        ]);

        NavigationItem::create($validatedData);

        return redirect()->route('navigation.index')->with('success', 'Item añadido correctamente.');
    }

    // Implementa otros métodos como update, destroy, etc.
}
