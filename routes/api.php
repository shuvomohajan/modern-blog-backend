<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::put('change-password', [AuthController::class, 'changePassword']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);
});
