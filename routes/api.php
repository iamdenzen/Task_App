<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\TagApiController;
use App\Http\Controllers\Api\UserApiController;
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

// https://documenter.gettaskman.com/view/8942795/TVmJhJv2

Route::post('registration', [UserApiController::class, 'store']);
Route::post('login', [UserApiController::class, 'login']);
Route::post('forgot-password', [UserApiController::class, 'forgotPassword']);
Route::get('show/{id}', [UserApiController::class, 'show']);

Route::get('user/{id}', [UserApiController::class, 'show']);
Route::get('user/{id}/tasks', [UserApiController::class, 'tasks']);
Route::get('user/{id}/comments', [UserApiController::class, 'comments']);

Route::get('categories', [CategoryApiController::class, 'index']);
Route::get('categories/{id}/tasks', [CategoryApiController::class, 'tasks']);

Route::get('tasks', [TaskApiController::class, 'index']);
Route::get('tasks/{id}', [TaskApiController::class, 'show']);
Route::get('tasks/{id}/comments', [TaskApiController::class, 'comments']);

Route::get('tags/{id}/tasks', [TagApiController::class, 'tasks']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('comments/tasks', [CommentApiController::class, 'store']);
    Route::post('logout', [UserApiController::class, 'logout']);
    Route::post('update-password',[UserApiController::class, 'updatePassword']);
});
