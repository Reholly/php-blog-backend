<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->with('tags');

        if ($request->has('tag')) {
            $tags = explode(',', $request->tag);
            $query->whereHas('tags', function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->where('title', 'LIKE', "%{$tag}%");
                }
            });
        }

        $articles = $query->paginate(25);
        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        $data['author'] = $user->login;

        $article = Article::create($data);

        return response()->json($article, 201);
    }

    public function update(Request $request, $id)
    {
        // Проверка роли
        $article = Article::findOrFail($id);
        $article->update($request->all());
        return response()->json($article, 200);
    }

    public function destroy($id)
    {
        // Проверка роли
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(null, 204);
    }
}
