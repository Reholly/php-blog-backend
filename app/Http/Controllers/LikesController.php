<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends Controller
{
    public function setLike(Article $article): JsonResponse
    {
        $userId = auth()->user()->id;
        if (Like::query()->where('user_id', $userId)->where('article_id', $article->id)->exists()) {
            return response()->json(['message' => 'already liked'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $like = Like::create([
            'user_id' => $userId,
            'article_id' => $article->id
        ]);

        return response()->json($like, Response::HTTP_CREATED);
    }

    public function deleteLike(Article $article): JsonResponse
    {
        $userId = auth()->user()->id;

        $like = Like::query()->where('article_id', $article->id)->where('user_id', $userId)->first();
        $like->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
