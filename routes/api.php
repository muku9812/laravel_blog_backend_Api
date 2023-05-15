<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//public Route
Route::post('/register',[\App\Http\Controllers\AuthController::class,'register']);
Route::post('/login',[\App\Http\Controllers\AuthController::class,'login']);


//protected Routes
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::get('/user',[\App\Http\Controllers\AuthController::class,'show']);

    Route::put('/user',[\App\Http\Controllers\AuthController::class,'update']);
    Route::post('/logout',[\App\Http\Controllers\AuthController::class,'logout']);

    //posts
    Route::get('/posts',[\App\Http\Controllers\PostController::class,'index']);
    Route::post('/posts',[\App\Http\Controllers\PostController::class,'store']);
    Route::get('/posts/{id}',[\App\Http\Controllers\PostController::class,'show']);
    Route::put('/posts/{id}',[\App\Http\Controllers\PostController::class,'update']);
    Route::delete('/posts/{id}',[\App\Http\Controllers\PostController::class,'destroy']);

    //comments
    Route::get('/posts/{id}/comments',[\App\Http\Controllers\CommentController::class,'index']);
    Route::post('/posts/{id}/comments',[\App\Http\Controllers\CommentController::class,'store']);
    Route::put('/comments/{id}',[\App\Http\Controllers\CommentController::class,'update']);
    Route::delete('/comments/{id}',[\App\Http\Controllers\CommentController::class,'destroy']);

    //likes
    Route::post('/posts/{id}/likes',[\App\Http\Controllers\LikeController::class,'likeOrUnlike']);

});
