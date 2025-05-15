<?php

use app\Http\Controllers\ArticleController;
use app\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::apiResource('articles', ArticleController::class)->except(['create', 'edit']);
Route::post('articles/{article}/comments', [CommentController::class, 'store']);
Route::get('articles/{article}/comments', [CommentController::class, 'index']);
Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
