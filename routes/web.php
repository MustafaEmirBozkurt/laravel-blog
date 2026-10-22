<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\BlogAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/yazi/{id}', [PostController::class, 'show'])->whereNumber('id')->name('posts.show');

Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');
Route::post('/giris', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::get('/kayit', [AuthController::class, 'showRegister'])->name('register');
Route::post('/kayit', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.post');
Route::post('/cikis', [AuthController::class, 'logout'])->name('logout');

Route::middleware(BlogAuth::class)->prefix('yonetim')->group(function () {
    Route::get('/yazi/olustur', [PostController::class, 'create'])->name('posts.create');
    Route::post('/yazi', [PostController::class, 'store'])->name('posts.store');
    Route::get('/yazi/{id}/duzenle', [PostController::class, 'edit'])->whereNumber('id')->name('posts.edit');
    Route::put('/yazi/{id}', [PostController::class, 'update'])->whereNumber('id')->name('posts.update');
    Route::delete('/yazi/{id}', [PostController::class, 'destroy'])->whereNumber('id')->name('posts.destroy');
});
