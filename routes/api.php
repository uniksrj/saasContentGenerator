<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\GenerationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Middleware\EnsureSubscribed;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects', [ProjectController::class, 'index']);

    Route::get('/topics/{project}', [TopicController::class, 'index']);
    Route::post('/generate/{project}', [GenerationController::class, 'store'])
        ->middleware(EnsureSubscribed::class);
    Route::get('/articles/{project}', [ArticleController::class, 'index']);

    Route::get('/projects/{project}/topics', [TopicController::class, 'index']);
    Route::post('/projects/{project}/topics/{topic}/generate', [TopicController::class, 'generate'])
        ->middleware(EnsureSubscribed::class);

    Route::post('/projects/{project}/generate', [GenerationController::class, 'store'])
        ->middleware(EnsureSubscribed::class);

    Route::get('/projects/{project}/articles', [ArticleController::class, 'index']);
    Route::put('/projects/{project}/articles/{article}', [ArticleController::class, 'update']);
    Route::patch('/projects/{project}/articles/{article}/publish', [ArticleController::class, 'publish']);
});
