<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::apiResource('/posts', PostController::class)->except('show');
    Route::apiResource('/comments', CommentController::class);
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
