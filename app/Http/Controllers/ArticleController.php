<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->with('tags', 'comments.user', 'category');

        if ($request->has('tag')) {
            $tags = explode(',', $request->tag);
            $query->whereHas('tags', function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->where('title', 'LIKE', "%{$tag}%");
                }
            });
        }

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->category.'%');
            });
        }

        $articles = $query->paginate(25);
        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::with('tags', 'comments.user', 'category')->findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $article = Article::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'author' => $user->login,
            'category_id' => $validatedData['category_id'] ?? null,
        ]);


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
