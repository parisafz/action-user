<?php

use App\Http\Controllers\Api\ApiProfileController;
use App\Http\Controllers\Api\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// عملیات CRUD برای کاربران
Route::apiResource('users', ApiUserController::class);

// عملیات نمایش و ویرایش کاربر جاری
// Route::apiResource('profile', ApiProfileController::class);
Route::group(['prefix' => 'profile', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [ApiProfileController::class, 'show']);
    Route::put('/', [ApiProfileController::class, 'update']);
});

// عملیات LOGIN و LOGOUT
Route::post('/login', [ApiProfileController::class, 'login']);
Route::post('/logout', [ApiProfileController::class, 'logout'])->middleware('auth:sanctum');
