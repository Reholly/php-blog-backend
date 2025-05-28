<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
   public function index(Request $request)
   {
       $currentUser = auth()->user();

       $query = Article::query()
           ->where('is_approved', true)
           ->with('tags', 'comments.user', 'category')
           ->withCount('likes');

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

       $articles->getCollection()->transform(function ($article) use ($currentUser) {
           if ($currentUser) {
               $article->liked_by_current_user = $article->likes()->where('user_id', $currentUser->id)->exists();
           } else {
               $article->liked_by_current_user = false;
           }

           return $article;
       });

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
        $article = Article::findOrFail($id);
        if ($article->author != auth()->user()->login) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $article->update($request->all());
        return response()->json($article, 200);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $currentUser = auth()->user();
        if ($currentUser->role == UserRole::USER && $article->author !== auth()->user()->login) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $article->comments()->delete();
        $article->likes()->delete();
        $article->tags()->delete();
        $article->category()->delete();
        $article->delete();

        return response()->json(null, 204);
    }


    public function approve(Article $article): JsonResponse
    {
        $article->update(['is_approved' => true]);

        return response()->json([], Response::HTTP_OK);
    }

    public function indexNoApproved(Request $request)
    {
        $currentUserId = auth()->user()->id;

        $query = Article::query()
            ->where('is_approved', false)
            ->with('tags', 'comments.user', 'category')
            ->withCount('likes');

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

        $articles = $query->get();

        $articles->transform(function ($article) use ($currentUserId) {
            if ($currentUserId) {
                $article->liked_by_current_user = $article->likes()->where('user_id', $currentUserId)->exists();
            } else {
                $article->liked_by_current_user = false;
            }

            return $article;
        });

        return response()->json($articles);
    }

}
