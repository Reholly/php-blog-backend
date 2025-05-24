<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
// Добавление комментария
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate(['content' => 'required|string']);

        $comment = $article->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        return response()->json($comment, 201);
    }

    // Удаление комментария
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
    }
}
