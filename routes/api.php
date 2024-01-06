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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
        Route::post('login', 'login');
    });
});

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('teams', \App\Http\Controllers\TeamController::class);
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('projects', \App\Http\Controllers\ProjectController::class);
    Route::apiResource('stages', \App\Http\Controllers\StageController::class);
    Route::apiResource('tasks', \App\Http\Controllers\TaskController::class);
    Route::apiResource('permissions', \App\Http\Controllers\PermissionController::class);
    Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
    Route::apiResource('tickets', \App\Http\Controllers\TicketController::class);
});
