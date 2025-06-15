<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\AreaDocuments;
use App\Http\Controllers\MediaSearchController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/media/filters', [MediaSearchController::class, 'filters']);
Route::get('/media/parameters', [MediaSearchController::class, 'parameters']);
Route::get('/media/search', [MediaSearchController::class, 'search']);

// Public routes
Route::get('/', fn() => view('index'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');

// Login routes (public)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('filament.auth.attempt');

// Redirect /admin/login to custom login page
Route::get('/admin/login', fn() => redirect()->route('login'))->name('filament.admin.auth.login');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {


    Route::get('/admin/area-documents/{area}', AreaDocuments::class)
        ->name('filament.admin.pages.area-documents');

    // Logout route
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('filament.admin.auth.logout');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});