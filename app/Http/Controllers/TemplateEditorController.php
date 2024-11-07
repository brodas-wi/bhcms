<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateEditorController extends Controller
{
    public function edit($id = null)
    {
        if ($id) {
            $template = Template::findOrFail($id);
        } else {
            $template = new Template();
            $template->html = view('templates.default_template')->render();
        }
        return view('templates.editor', compact('template'));
    }

    public function save(Request $request, $id = null)
    {
        $template = $id ? Template::findOrFail($id) : new Template();
        $template->name = $request->input('name');
        $template->html = $request->input('html');
        $template->css = $request->input('css');
        $template->save();

        return response()->json(['success' => true, 'id' => $template->id]);
    }

    public function index()
    {
        $templates = Template::all();
        return view('templates.index', compact('templates'));
    }
}
