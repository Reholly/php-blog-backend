<?php

namespace app\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'author' => 'required|string',
            'text' => 'required|string',
        ]);

        return $this->commentService->createComment($data);
    }

    public function delete($id)
    {
        $user = auth()->user(); // Получаем текущего авторизованного пользователя
        return $this->commentService->deleteComment($id, $user);
    }

    public function index($articleId)
    {
        return $this->commentService->getCommentsByArticle($articleId);
    }
}
