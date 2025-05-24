<?php

namespace app\Repository;

use App\Models\Comment;

class CommentRepository
{
    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function findById($id): ?Comment
    {
        return Comment::find($id);
    }

    public function getCommentsByArticleId($articleId)
    {
        return Comment::where('article_id', $articleId)->get();
    }
}
