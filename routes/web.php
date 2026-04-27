<?php

use App\Http\Controllers\Blogmain;
use App\Http\Controllers\Saas\AdminController as SaasAdminController;
use App\Http\Controllers\Saas\ArticleController;
use App\Http\Controllers\Saas\AuthController;
use App\Http\Controllers\Saas\BillingController;
use App\Http\Controllers\Saas\DashboardController;
use App\Http\Controllers\Saas\ProjectController;
use App\Http\Controllers\Saas\SettingController;
use App\Http\Controllers\Saas\TopicController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureSubscribed;
use Illuminate\Support\Facades\Route;

Route::get('/', [Blogmain::class, 'main'])->name('home');
 
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('saas.login');
    Route::post('/login', [AuthController::class, 'login'])->name('saas.login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('saas.register');
    Route::post('/register', [AuthController::class, 'register'])->name('saas.register.submit');
});

Route::middleware('auth')->prefix('app')->name('saas.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/{project}/switch', [ProjectController::class, 'switch'])->name('projects.switch');

    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
    Route::post('/topics/fetch', [TopicController::class, 'fetch'])->name('topics.fetch');
    Route::post('/topics/{topic}/generate', [TopicController::class, 'generate'])
        ->middleware(EnsureSubscribed::class)
        ->name('topics.generate');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::post('/articles/generate', [ArticleController::class, 'generate'])
        ->middleware(EnsureSubscribed::class)
        ->name('articles.generate');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::patch('/articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/subscription', [BillingController::class, 'subscribe'])->name('settings.subscription');
    Route::post('/settings/subscription/intent', [BillingController::class, 'embeddedSubscribe'])->name('settings.subscription.intent');
    Route::post('/settings/subscription/sync', [BillingController::class, 'sync'])->name('settings.subscription.sync');
    Route::get('/settings/subscription/success', [BillingController::class, 'success'])->name('settings.subscription.success');
    Route::get('/settings/subscription/cancelled', [BillingController::class, 'cancelled'])->name('settings.subscription.cancelled');
    Route::post('/settings/subscription/cancel', [BillingController::class, 'cancel'])->name('settings.subscription.cancel');

    Route::middleware(EnsureAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [SaasAdminController::class, 'index'])->name('index');
        Route::patch('/users/{user}', [SaasAdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/plans/{plan}', [SaasAdminController::class, 'updatePlan'])->name('plans.update');
    });
});

Route::post('/stripe/webhook', [BillingController::class, 'webhook'])->name('stripe.webhook');

// Public editorial routes.
Route::get('/categories', [Blogmain::class, 'categories'])->name('categories.index');
Route::get('/category/{categorySlug}', [Blogmain::class, 'byCategory'])->name('category.show');
Route::get('/blog', [Blogmain::class, 'index'])->name('blog.index');
Route::get('/blog/{slugOrId}', [Blogmain::class, 'show'])->name('blog.show');
Route::get('/posts/{slugOrId}', [Blogmain::class, 'show'])->name('post.show');

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contactUs')->name('contact');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy.policy');
Route::view('/terms-conditions', 'pages.terms-conditions')->name('terms.conditions');
Route::view('/disclaimer', 'pages.disclaimer')->name('disclaimer');
