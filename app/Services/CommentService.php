<?php

namespace app\Services;

namespace App\Services;

use App\Repository\CommentRepository;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(array $data)
    {
        return $this->commentRepository->create($data);
    }

    public function deleteComment($commentId, $user)
    {
        $comment = $this->commentRepository->findById($commentId);
        // Ваша логика проверки прав доступа
        if ($comment && ($comment->author === $user || $user->isAdmin())) {
            $this->commentRepository->delete($comment);
        }
    }

    public function getCommentsByArticle($articleId)
    {
        return $this->commentRepository->getCommentsByArticleId($articleId);
    }
}
