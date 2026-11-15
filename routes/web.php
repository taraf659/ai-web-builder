<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/projects', function () {
    return view('projects');
})->name('projects');

Route::get('/help', function () {
    return view('help');
})->name('help');

// API routes
Route::post('/api/chat', [ChatController::class, 'chat'])->name('api.chat');
Route::get('/api/chat/history', [ChatController::class, 'getHistory'])->name('api.chat.history');
Route::get('/api/chat/sessions', [ChatController::class, 'getSessions'])->name('api.chat.sessions');

// Project API routes
Route::prefix('api')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('api.projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('api.projects.store');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('api.projects.show');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('api.projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('api.projects.destroy');
});
