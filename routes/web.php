<?php

use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductsController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['maintenance'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductsController::class, 'index'])->name('products');
    Route::get('/products/{identifier}', [ProductsController::class, 'show'])->name('product.show');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    // Reviews — public submit only
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
/*
|--------------------------------------------------------------------------
| ADMIN AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // Login / Logout
    Route::get('/login', [AdminLoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    /*
    |----------------------------------------------------------------------
    | PROTECTED ADMIN ROUTES
    |----------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Products
        Route::resource('products', ProductController::class)->names([
            'index'   => 'admin.products.index',
            'create'  => 'admin.products.create',
            'store'   => 'admin.products.store',
            'show'    => 'admin.products.show',
            'edit'    => 'admin.products.edit',
            'update'  => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]);

        // Messages
        Route::get('/messages', [MessagesController::class, 'index'])->name('admin.messages.index');
        Route::get('/messages/{message}', [MessagesController::class, 'show'])->name('admin.messages.show');
        Route::delete('/messages/{message}', [MessagesController::class, 'destroy'])->name('admin.messages.destroy');


        // Orders (full resource)
        Route::resource('orders', OrderController::class)->names([
            'index'   => 'admin.orders.index',
            'create'  => 'admin.orders.create',
            'store'   => 'admin.orders.store',
            'show'    => 'admin.orders.show',
            'edit'    => 'admin.orders.edit',
            'update'  => 'admin.orders.update',
            'destroy' => 'admin.orders.destroy',
        ]);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');

        // Reviews admin management
        Route::get('/reviews',                    [AdminReviewController::class, 'index'])->name('admin.reviews.index');
        Route::patch('/reviews/{review}/approve',   [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::patch('/reviews/{review}/reject',    [AdminReviewController::class, 'reject'])->name('admin.reviews.reject');
        Route::delete('/reviews/{review}',           [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

        // ── Notifications (AJAX) ────────────────────────────────────────
        Route::get('/notifications/feed',           [NotificationsController::class, 'feed'])->name('admin.notifications.feed');
        Route::patch('/notifications/{notification}/read', [NotificationsController::class, 'markRead'])->name('admin.notifications.read');
        Route::post('/notifications/mark-all-read',  [NotificationsController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');


        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings/group/{group}', [SettingsController::class, 'saveGroup'])->name('admin.settings.save-group');
        Route::post('/settings/password', [SettingsController::class, 'changePassword'])->name('admin.settings.password');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('admin.settings.clear-cache');
        Route::post('/settings/clear-messages', [SettingsController::class, 'clearReadMessages'])->name('admin.settings.clear-messages');
    });
});
