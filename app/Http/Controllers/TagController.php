<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->tags);
    }



    public function show(Request $request, int $id)
    {
        if (!$tag = $request->user()->tags()->find($id)) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        return response()->json($tag);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        $tag = $request->user()->tags()->create($validated);

        return response()->json([
            'message' => 'Tag created successfully',
            'expense' => $tag
        ], 201);
    }



    public function delete(Request $request, int $id)
    {
        if (!$tag = $request->user()->tag()->find($id)) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }
}
