<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $currentUser = auth()->user();
        if ($currentUser->role != UserRole::ADMIN && $comment->user_id != $currentUser->id) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $comment->delete();
        return response()->json(null, 204);
    }
}
