<?php

use Illuminate\Support\Facades\Route;

// ============================================
// Auth Components
// ============================================
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

// ============================================
// Guest Components
// ============================================
use App\Livewire\Guess;
use App\Livewire\Guest\DetailArticle as GuestDetailArticle;
use App\Livewire\Guest\DetailProfile as GuestDetailProfile;

// ============================================
// User Components
// ============================================
use App\Livewire\CreateArticle;
use App\Livewire\Dashboard;
use App\Livewire\DetailArticle;
use App\Livewire\DetailProfile;
use App\Livewire\EditArticle;
use App\Livewire\Guidelines;
use App\Livewire\LandingPage;
use App\Livewire\Profile;
use App\Livewire\Profile\EditProfile;

// ============================================
// Admin Components
// ============================================
use App\Livewire\Admin\Comments;
use App\Livewire\ArticleControl;
use App\Livewire\Categories;
use App\Livewire\CreateCategory;
use App\Livewire\Statistic;
use App\Livewire\UserControl;

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/', LandingPage::class)->name('landing-page');

// ============================================
// GUEST ROUTES (Unauthenticated Users)
// ============================================

Route::prefix('guest')->group(function () {
    Route::get('/', Guess::class)->name('guest');
    Route::get('/article/{slug}', GuestDetailArticle::class)->name('detail-article-guest');
    Route::get('/profile/{slug}', GuestDetailProfile::class)->name('detail-profile-guest');
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// ============================================
// AUTHENTICATED USER ROUTES
// ============================================

Route::middleware(['auth', 'banned'])->group(function () {

    // Dashboard & Guidelines
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/guidelines', Guidelines::class)->name('guidelines');

    // Profile Routes
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/profile/edit', EditProfile::class)->name('profile-edit');
    Route::get('/detail-profile/{slug}', DetailProfile::class)->name('detail-profile');

    // Article Routes
    Route::get('/create-article', CreateArticle::class)
        ->name('create-article')
        ->middleware('throttle:10,60');
    Route::get('/edit-article/{slug}', EditArticle::class)->name('edit-article');
    Route::get('/detail-article/{slug}', DetailArticle::class)->name('detail-article');

    // ============================================
    // ADMIN ROUTES
    // ============================================

    Route::prefix('admin')->middleware('is_admin')->group(function () {
        // Statistics & Overview
        Route::get('/statistic', Statistic::class)->name('statistic');

        // Content Management
        Route::get('/blog-control', ArticleControl::class)->name('blog-control');
        Route::get('/comment-control', Comments::class)->name('comment-control');

        // User Management
        Route::get('/user-control', UserControl::class)->name('user-control');

        // Category Management
        Route::get('/category', Categories::class)->name('category');
        Route::get('/create-category', CreateCategory::class)->name('create-category');
    });
});

// ============================================
// FALLBACK ROUTE (404 Not Found)
// ============================================

Route::fallback(function () {
    return view('livewire.not-found');
});
