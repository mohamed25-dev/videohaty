<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'index'])->name('main');

Route::get('/channels/search', [ChannelController::class, 'search'])->name('channels.search');
Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('layouts.main');
})->name('dashboard');

Route::get('/videos/search', [VideoController::class, 'search'])->name('videos.search');
Route::post('/views', [VideoController::class, 'incrementViews'])->name('incrementViews');
Route::resource('/videos', VideoController::class);

Route::post('/likes', [LikeController::class, 'likeVideo'])->name('likes');

Route::post('/coments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/coments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::patch('/coments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/coments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::delete('/history/desroyAll', [HistoryController::class, 'destroyAll'])->name('history.distroyAll');
Route::delete('/history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');

Route::prefix('/admin')->middleware('can:update-videos')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/channels', [ChannelController::class, 'adminIndex'])->name('admin.channels.index');
    Route::get('/mostViewedVideos', [VideoController::class, 'mostViewedVideos'])->name('admin.most.viewed.videos');
    Route::get('/channels/permissions', [ChannelController::class, 'adminPermissions'])->name('admin.channels.permissions');
    Route::get('/channels/blocked', [ChannelController::class, 'indexBlocked'])->name('admin.channels.blocked');
    Route::patch('/{user}/unblock', [ChannelController::class, 'adminUnblock'])->name('admin.channels.unblock')->middleware('can:update-users');
    Route::patch('/{user}/block', [ChannelController::class, 'adminBlock'])->name('admin.channels.block')->middleware('can:update-users');
    Route::delete('/{user}', [ChannelController::class, 'adminDestroy'])->name('admin.channels.delete')->middleware('can:update-users');
    Route::patch('/{user}', [ChannelController::class, 'adminUpdate'])->name('admin.channels.update')->middleware('can:update-users');
});

