<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use app\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UsersController;
use App\Models\UserRole;
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

// Users
Route::post('/users/grant-role', [UsersController::class, 'grantRole'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::ADMIN]);

Route::delete('/users/{id}', [UsersController::class, 'deleteUser'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::ADMIN]);

Route::middleware(['auth:api', 'requireRole:' . UserRole::ADMIN])->group(function () {
    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
});

Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags/{tag}/attach', [TagController::class, 'attach']);
Route::post('/tags/{tag}/detach', [TagController::class, 'detach']);
