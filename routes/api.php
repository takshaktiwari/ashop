<?php

use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Api\AddressController;
use Takshak\Ashop\Http\Controllers\Api\CartController;
use Takshak\Ashop\Http\Controllers\Api\CategoryController;
use Takshak\Ashop\Http\Controllers\Api\FavoriteController;
use Takshak\Ashop\Http\Controllers\Api\ProductController;
use Takshak\Ashop\Http\Controllers\Api\ReviewController;
use Takshak\Ashop\Http\Middleware\ApiUserMiddleware;

Route::middleware('api')
    ->prefix('api/shop')
    ->group(function () {
        Route::middleware([ApiUserMiddleware::class])->group(function () {
            Route::get('categories', [CategoryController::class, 'index']);
            Route::get('categories/with-products', [CategoryController::class, 'withProducts']);
            Route::get('categories/{category}', [CategoryController::class, 'show']);

            Route::prefix('products')->group(function () {
                Route::get('/', [ProductController::class, 'index']);
                Route::get('popular', [ProductController::class, 'popular']);
                Route::get('similar', [ProductController::class, 'similar']);
                Route::get('recommended', [ProductController::class, 'recommended']);
                Route::get('{product}', [ProductController::class, 'show']);
            });

            Route::prefix('carts')->group(function () {
                Route::get('/', [CartController::class, 'index']);
                Route::post('store', [CartController::class, 'store']);
                Route::post('update/{cart}', [CartController::class, 'update']);
                Route::post('delete/{cart}', [CartController::class, 'delete']);
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::prefix('favorites')->group(function () {
                Route::get('/', [FavoriteController::class, 'index']);
                Route::post('add-remove', [FavoriteController::class, 'itemToggle']);
            });

            Route::post('reviews', [ReviewController::class, 'store']);

            Route::get('addresses', [AddressController::class, 'index']);
            Route::post('addresses', [AddressController::class, 'store']);
            Route::get('addresses/{address}', [AddressController::class, 'show']);
            Route::post('addresses/{address}', [AddressController::class, 'update']);
            Route::post('addresses/{address}/delete', [AddressController::class, 'delete']);
        });
    });
