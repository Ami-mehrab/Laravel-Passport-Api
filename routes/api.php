<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialAuthController; // Social auth controller import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Social auth routes - Google
Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);




Route::middleware('auth:api')->group(function () {
    

    Route::get('/user', [AuthController::class, 'userProfile']);
    

    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    
});