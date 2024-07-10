<?php

use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Api\CartController;
use Takshak\Ashop\Http\Controllers\Api\CategoryController;
use Takshak\Ashop\Http\Controllers\Api\ProductController;

Route::middleware('api')
    ->prefix('api/shop')
    ->group(function () {
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{category}', [CategoryController::class, 'show']);

        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('{product}', [ProductController::class, 'show']);
            Route::get('popular', [ProductController::class, 'popular']);
        });

        Route::prefix('carts')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('store', [CartController::class, 'store']);
            Route::post('update/{cart}', [CartController::class, 'update']);
            Route::post('delete/{cart}', [CartController::class, 'delete']);
        });
    });
