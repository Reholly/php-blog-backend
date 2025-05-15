<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::paginate(25);
        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request)
    {
        // Проверка на наличие ролей (заглушка)
        // $this->authorize('create', Article::class);
        $article = Article::create($request->all());
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
