<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalRecords\VaccineController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes with Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/animals', [AnimalController::class, 'index']);
    Route::post('/animals', [AnimalController::class, 'store']);
    Route::get('/animals/{animal}', [AnimalController::class, 'show']);
    Route::put('/animals/{animal}', [AnimalController::class, 'update']);
    Route::delete('/animals/{animal}', [AnimalController::class, 'destroy']);
    Route::post('/animals/{animal}/upload-image', [AnimalController::class, 'uploadImage']);

    Route::get('/animals/{animal}/vaccines', [VaccineController::class, 'index']);
    Route::post('/animals/{animal}/vaccines', [VaccineController::class, 'store']);
});
