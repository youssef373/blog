<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $posts = Post::all();
    return view('post.index', compact('posts'));
});

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::get('/login_form', [UserController::class, 'loginForm'])->name('login-form');
Route::get('/register_form', [UserController::class, 'registerForm'])->name('register-form');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::resource('posts', PostController::class)->except('show');
    Route::resource('comments', CommentController::class);
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
