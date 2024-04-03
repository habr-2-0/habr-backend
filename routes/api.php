<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\Localization;
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

Route::middleware(Localization::class)->group(callback: function () {
    Route::controller(LoginRegisterController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:sanctum')->group(callback: function () {
        Route::post('/logout', [LoginRegisterController::class, 'logout']);

        //    users crud
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index');
            Route::get('users/{id}', 'show');
            Route::put('users', 'update');
            Route::post('users/upload', 'upload');
        });

        //    User Posts Routes
        Route::get('users/{user_id}/posts', [UserController::class, 'getUserPosts']);
        Route::get('users/{user_id}/posts/{post_id}', [UserController::class, 'getUserPostById']);

        Route::get('user/posts/{post_id}/comments', [CommentController::class, 'getUserPostComments']);
        Route::get('posts/{post_id}/comments', [CommentController::class, 'getPostComments']);


        //    comments crud
        Route::get('comments', [CommentController::class, 'index']);
        Route::post('comments/{id}', [CommentController::class, 'store']);
        Route::put('comments', [CommentController::class, 'update']);


        Route::get('comments/{id}', [CommentController::class, 'show']);
        Route::delete('comments/{id}', [CommentController::class, 'destroy']);

        // posts crud
        Route::get('posts', [PostController::class, 'index']);
        Route::get('posts/{id}', [PostController::class, 'show']);
        Route::delete('posts/{id}', [PostController::class, 'destroy']);
        Route::post('posts', [PostController::class, 'store']);
        Route::put('posts/{id}', [PostController::class, 'update']);

        // follows crud
        Route::delete('follows/{id}', [FollowController::class, 'destroy']);
        Route::post('follows/{id}', [FollowController::class, 'store']);
        Route::get('follows/followings/{id}', [FollowController::class, 'getUserFollowings']);
        Route::get('follows/followers/{id}', [FollowController::class, 'getUserFollowers']);
    });

});
