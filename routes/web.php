<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UsersController;
use App\Models\UserRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Articles
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::post('/articles', [ArticleController::class, 'store'])
    ->middleware(['auth:api']);
Route::put('/articles/{id}', [ArticleController::class, 'update'])
    ->middleware(['auth:api']);
Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])
    ->middleware(['auth:api']);
Route::patch('/articles/{article}/approve', [ArticleController::class, 'approve'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::MODERATOR]);
Route::get('/articles/not-approved', [ArticleController::class, 'indexNoApproved'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::ADMIN]);

// Comments
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
    ->middleware(['auth:api']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware(['auth:api']);

// Likes
Route::post('/articles/{article}/likes', [LikesController::class, 'setLike'])
    ->middleware(['auth:api']);
Route::delete('/articles/{article}/likes', [LikesController::class, 'deleteLike'])
    ->middleware(['auth:api']);

// Auth
Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);

// Users
Route::post('/users/grant-role', [UsersController::class, 'grantRole'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::ADMIN]);

Route::delete('/users/{id}', [UsersController::class, 'deleteUser'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::ADMIN]);

Route::get('/users', [UsersController::class, 'index'])
    ->middleware(['auth:api', 'requireRole:' . UserRole::ADMIN]);

// Tags
Route::middleware(['auth:api'])->group(function () {
    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags/{tag}/attach', [TagController::class, 'attach']);
    Route::post('/tags/{tag}/detach', [TagController::class, 'detach']);
});

// Categories
Route::apiResource('categories', CategoryController::class)
    ->middleware(['auth:api']);

Route::get('/categories', [CategoryController::class, 'index']) // Публичный доступ
    ->middleware(['auth:api']);

