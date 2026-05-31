<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{identifier}', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/articles/{articleId}/comments', [CommentController::class, 'index']);

// Bug Fix #2 & #3: Endpoint publik untuk pencarian user dan profil publik user lain
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{slug}', [UserController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::match(['post', 'put', 'patch'], '/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Articles
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::match(['post', 'put', 'patch'], '/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);

    // Comments
    Route::post('/articles/{articleId}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Likes
    Route::post('/articles/{articleId}/like', [LikeController::class, 'toggle']);
});
