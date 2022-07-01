<?php

use Illuminate\Support\Facades\Route;

// Login
Route::post('auth/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    // Logout
    Route::post('auth/logout', [App\Http\Controllers\AuthController::class, 'logout']);

    // Get Authenticated User
    Route::get('auth/user', [App\Http\Controllers\AuthController::class, 'user']);

    // Users
    Route::match(['put', 'patch'], 'users/{user}/restore', [App\Http\Controllers\UsersController::class, 'restore']);
    Route::delete('users/{user}/force-delete', [App\Http\Controllers\UsersController::class, 'forceDestroy']);
    Route::get('users/{user}/assigned-roles', [App\Http\Controllers\UsersController::class, 'getAssignedRoles']);
    Route::post('users/toggle-role', [App\Http\Controllers\UsersController::class, 'toggleRole']);
    Route::apiResource('users', App\Http\Controllers\UsersController::class);
});
