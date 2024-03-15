<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group( callback: function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout']);

    Route::controller(UserController::class)->group(function() {
        Route::post('/users', 'store');
        Route::get('/users', 'index');
        Route::get('/users/{id}', 'show');
        Route::post('/users/{id}', 'update');
        Route::delete('/users/{id}', 'destroy');
    });
});
