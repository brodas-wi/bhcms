<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Get all tags.
     */
    public function index()
    {
        $tags = Tag::orderBy('name')->get();
        return response()->json($tags);
    }

    /**
     * Store a new tag.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:tags,name'
            ]);

            $tag = Tag::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return response()->json([
                'success' => true,
                'tag' => $tag
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a tag.
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tag deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Search tags.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $tags = Tag::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($tags);
    }
}
