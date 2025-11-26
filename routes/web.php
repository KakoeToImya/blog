<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendsController;

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

Route::get('/profile', [ProfileController::class,'show'])->name('profile.show');



//друзья
Route::middleware(['auth'])->group(function () {
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendsController::class, 'index'])->name('friends.index');
        Route::get('/search', [FriendsController::class, 'search'])->name('friends.search');
        Route::post('/send-request/{user}', [FriendsController::class, 'send'])->name('friends.send');
        Route::post('/accept-request/{user}', [FriendsController::class, 'accept'])->name('friends.accept');
        Route::post('/reject-request/{user}', [FriendsController::class, 'reject'])->name('friends.reject');
        Route::post('/cancel-request/{user}', [FriendsController::class, 'cancel'])->name('friends.cancel');
        Route::post('/remove-friend/{user}', [FriendsController::class, 'remove'])->name('friends.remove');
    });
});


// уведомления
Route::middleware(['auth'])->group(function () {
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/', [NotificationController::class, 'clearAll'])->name('notifications.clear');
    });
});
Route::get('/about', function () {
    return view('about');
})->name('about');


require __DIR__.'/auth.php';
