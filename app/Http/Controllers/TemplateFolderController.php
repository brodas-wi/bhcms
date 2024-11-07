<?php

namespace App\Http\Controllers;

use App\Models\TemplateFolder;
use Illuminate\Http\Request;

class TemplateFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folders = TemplateFolder::with('templates')->get();
        return view('template_folders.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        TemplateFolder::create($validatedData);

        return redirect()->route('template_folders.index')->with('success', 'Folder created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TemplateFolder $folder)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        $folder->update($validatedData);

        return redirect()->route('template_folders.index')->with('success', 'Folder updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemplateFolder $folder)
    {
        $folder->delete();
        return redirect()->route('template_folders.index')->with('success', 'Folder deleted successfully');
    }
}
