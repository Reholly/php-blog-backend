<?php

use app\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use app\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::apiResource('articles', ArticleController::class);
Route::post('/comments', [CommentController::class, 'store']);
Route::delete('/comments/{id}', [CommentController::class, 'delete']);
Route::get('/articles/{articleId}/comments', [CommentController::class, 'index']);

// Auth
Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);
