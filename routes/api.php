<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{identifier}', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/articles/{articleId}/comments', [CommentController::class, 'index']);


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{slug}', [UserController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::match(['post', 'put', 'patch'], '/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::match(['post', 'put', 'patch'], '/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('is_admin');

    
    Route::post('/articles/{articleId}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    
    Route::post('/articles/{articleId}/like', [LikeController::class, 'toggle']);
});
