<?php

use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PublicationController as AdminPublicationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReactionController;
use App\Http\Controllers\CommentReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationReactionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicationController::class, 'index'])->name('home');
Route::get('/articles/{publication:slug}', [PublicationController::class, 'show'])->name('publications.show');
Route::get('/recherche', SearchController::class)->name('search');
Route::get('/archives', [ArchiveController::class, 'index'])->name('archives.index');
Route::get('/archives/{year}/{month?}', [ArchiveController::class, 'show'])->name('archives.show')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{1,2}']);
Route::get('/auteur/{user:username}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/articles/{publication:slug}/reactions', [PublicationReactionController::class, 'toggle'])
        ->name('publications.reactions.toggle');
    Route::post('/articles/{publication:slug}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::post('/comments/{comment}/replies', [CommentController::class, 'reply'])
        ->name('comments.reply');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
    Route::post('/comments/{comment}/reactions', [CommentReactionController::class, 'toggle'])
        ->name('comments.reactions.toggle');
    Route::post('/comments/{comment}/reports', [CommentReportController::class, 'store'])
        ->name('comments.reports.store');
});

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('publications', AdminPublicationController::class)->except(['show']);

    Route::get('comments', [CommentModerationController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/hide', [CommentModerationController::class, 'hide'])->name('comments.hide');
    Route::patch('comments/{comment}/unhide', [CommentModerationController::class, 'unhide'])->name('comments.unhide');
    Route::delete('comments/{comment}/force', [CommentModerationController::class, 'destroy'])->name('comments.force-delete');
    Route::patch('reports/{report}', [CommentModerationController::class, 'updateReport'])->name('reports.update');

    Route::get('users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::get('pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
});

require __DIR__.'/auth.php';
