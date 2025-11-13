<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


Route::get('/', [PostController::class, 'home'])->name('home');

// аутентификация
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// посты
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
})->middleware('auth');
// категории
Route::get('/categories/{category:slug}', [PostController::class, 'category'])->name('posts.category');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Профиль 
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/about', function () {
    return view('about');
})->name('about');


require __DIR__.'/auth.php';