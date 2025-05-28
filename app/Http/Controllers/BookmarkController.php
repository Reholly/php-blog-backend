<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $bookmarks = $user->bookmarks()-with('article');

        return response()->json($bookmarks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $articleId)
    {
        $user = $request->user();

        if ($user->bookmarks()->where('article_id', $articleId)->exists()) {
            return response()->json(['message' => 'уже в закладках'], 409);
        }

        $bookmark = $user->bookmarks()->create(['article_id' => $articleId]);
        return response()->json($bookmark, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $articleId)
    {
        $request->user()->bookmarks()->where('article_id', $articleId)->delete();
        return response()->json(null, 204);
    }
}
