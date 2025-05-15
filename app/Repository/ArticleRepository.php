<?php

namespace app\Repository;

use App\Models\Article;

class ArticleRepository
{
    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function update(Article $article, array $data): Article
    {
        $article->update($data);
        return $article;
    }

    public function delete(Article $article): void
    {
        $article->delete();
    }

    public function findById($id): ?Article
    {
        return Article::find($id);
    }

    public function all($perPage = 25)
    {
        return Article::paginate($perPage);
    }

    public function findByAuthor($author, $perPage = 25)
    {
        return Article::where('author', $author)->paginate($perPage);
    }
}
