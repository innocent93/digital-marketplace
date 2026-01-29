<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PublicProductController;
use App\Http\Controllers\Api\V1\CreatorProductController;
use App\Http\Controllers\Api\V1\PurchaseController;
use App\Http\Controllers\Api\V1\DownloadController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('products', [PublicProductController::class, 'index']);
Route::get('products/{product}', [PublicProductController::class, 'show']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Creator Routes (PREFIXED)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:creator')->prefix('creator')->group(function () {
        Route::get('products', [CreatorProductController::class, 'index']);
        Route::post('products', [CreatorProductController::class, 'store']);
        Route::get('products/{product}', [CreatorProductController::class, 'show']);
        Route::put('products/{product}', [CreatorProductController::class, 'update']);
        Route::delete('products/{product}', [CreatorProductController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:customer')->group(function () {
        Route::post('checkout/{product}', [PurchaseController::class, 'checkout']);
        Route::get('library', [PurchaseController::class, 'library']);
        Route::get('download/{product}', [DownloadController::class, 'generate']);
    });
});
