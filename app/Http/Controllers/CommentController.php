<?php

namespace app\Http\Controllers;

use app\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = Comment::create($request->all());
        return response()->json($comment, 201);
    }

    public function index($articleId)
    {
        $comments = Comment::where('article_id', $articleId)->get();
        return response()->json($comments);
    }

    public function destroy($id)
    {
        // Проверка роли
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(null, 204);
    }
}
