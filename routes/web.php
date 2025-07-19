<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimalRecords\FamilyController;
use App\Http\Controllers\AnimalRecords\DiseaseController;
use App\Http\Controllers\AnimalRecords\VaccineController;
use App\Http\Controllers\AnimalRecords\MedicationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('home'))->name('home');

/*
|--------------------------------------------------------------------------
| Guest‑only Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register',  [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password',        [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password',       [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [AuthController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile',      [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Animal Management
    |--------------------------------------------------------------------------
    */
    Route::resource('animals', AnimalController::class)->except('show');
    Route::get('/animals/{animal}', [AnimalController::class, 'show'])->name('animals.show');

    /*
    |--------------------------------------------------------------------------
    | Animal Records Subsystem
    |--------------------------------------------------------------------------
    */
    Route::prefix('animal-records')->name('animal-records.')->group(function () {
        Route::resource('families',    FamilyController::class);
        Route::resource('diseases',    DiseaseController::class);
        Route::post('diseases/{disease}/cure', [DiseaseController::class, 'markAsCured'])
            ->name('diseases.cure');
        Route::resource('vaccines',    VaccineController::class);
        Route::resource('medications', MedicationController::class);
        Route::post('medications/{medication}/complete', [MedicationController::class, 'markAsCompleted'])
            ->name('medications.complete');
    });

    /*
    |--------------------------------------------------------------------------
    | Marketplace
    |--------------------------------------------------------------------------
    */
    Route::prefix('marketplace')->name('marketplace.')->group(function () {
        // 1. All Listings
        Route::get('/', [MarketplaceController::class, 'index'])->name('index');

        // 2. My Listings (before wildcard)
        Route::get('/mine', [MarketplaceController::class, 'myListings'])->name('my-listings');

        // 3. Create & Store Listing (before wildcard)
        Route::get('/create', [MarketplaceController::class, 'create'])->name('create');
        Route::post('/',       [MarketplaceController::class, 'store'])->name('store');

        // 4. Public & Private Chats (before wildcard)
        Route::get('/{listing}/chat/public',  [MarketplaceChatController::class, 'publicChat'])
            ->name('chat.public');
        Route::get('/{listing}/chat/private', [MarketplaceChatController::class, 'privateChat'])
            ->name('chat.private');
        Route::post('/chat/{chat}/message',   [MarketplaceChatController::class, 'sendMessage'])
            ->name('chat.message');

        // 5. Show / Edit / Update / Delete Listing (wildcard routes)
        Route::get('/{listing}',       [MarketplaceController::class, 'show'])->name('show');
        Route::get('/{listing}/edit',  [MarketplaceController::class, 'edit'])->name('edit');
        Route::put('/{listing}',       [MarketplaceController::class, 'update'])->name('update');
        Route::delete('/{listing}',    [MarketplaceController::class, 'destroy'])->name('destroy');
    });
});
