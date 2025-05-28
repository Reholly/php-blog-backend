<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request, $authorId)
    {
        $user = $request->user();
        $author = User::findOrFail($authorId);

        if ($user->id === $author->id) {
            return response()->json(['message' => 'нельзя подписаться на самого себя'], 400);
        }

        if ($user->following()->where('following_id', $authorId)->exists()) {
            return response()->json(['message' => 'я понимаю, что вы его очень любите, но подписаться можно только один раз'], 409);
        }

        $follow = $user->following()->create(['following_id' => $authorId]);
        return response()->json($follow, 201);
    }

    // Отписаться от автора
    public function unfollow(Request $request, $authorId)
    {
        $request->user()->following()->where('following_id', $authorId)->delete();
        return response()->json(null, 204);
    }

    public function feed(Request $request)
    {
        $followingIds = $request->user()->following()->pluck('following_id');

        $articles = Article::whereIn('author', $followingIds)
            ->with('tags', 'category')
            ->latest()
            ->paginate(15);

        return response()->json($articles);
    }
}
