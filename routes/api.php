<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Protected routes that need access token
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Route for user profile
    Route::get('/profile', [UserController::class, 'profile']);
});

// Route for registration API
Route::post('/register', [UserController::class, 'register']);

// Route for user verify
Route::get('/verify/{token}', [VerificationController::class, 'verify']);

// Route for login API
Route::post('/login', [UserController::class, 'login']);
