<?php

use App\Features\Auth\Controllers\LoginController;
use App\Features\Auth\Controllers\LogoutController;
use App\Features\Auth\Controllers\RegisterController;
use App\Features\Comments\Controllers\CommentSearchController;
use App\Features\Comments\Controllers\CreateCommentController;
use App\Features\Comments\Controllers\DeleteCommentController;
use App\Features\Comments\Controllers\EditCommentController;
use App\Features\Posts\Controllers\ShowPostController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class);
Route::post('sign-in', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('sign-out', LogoutController::class);  // исправил 'sing-out' -> 'sign-out'

    Route::middleware('abilities:comments:create')->post('/posts/{post}/comment', CreateCommentController::class);
    Route::middleware('abilities:comments:update')->put('/comments/{comment}', EditCommentController::class);
    Route::middleware('abilities:comments:delete')->delete('/comments/{comment}', DeleteCommentController::class);
});


Route::get('/comments/search', CommentSearchController::class);
Route::get('/posts/{post}', ShowPostController::class);
