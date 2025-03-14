<?php

use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Api\AddressController;
use Takshak\Ashop\Http\Controllers\Api\CartController;
use Takshak\Ashop\Http\Controllers\Api\CategoryController;
use Takshak\Ashop\Http\Controllers\Api\CheckoutController;
use Takshak\Ashop\Http\Controllers\Api\FavoriteController;
use Takshak\Ashop\Http\Controllers\Api\OrderController;
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
                Route::get('viewed/history', [ProductController::class, 'viewedHistory']);
            });

            Route::prefix('carts')->group(function () {
                Route::get('/', [CartController::class, 'index']);
                Route::post('store', [CartController::class, 'store']);
                Route::post('update/{cart}', [CartController::class, 'update']);
                Route::post('delete/{cart}', [CartController::class, 'delete']);
            });

            Route::get('reviews', [ReviewController::class, 'show']);
            Route::post('checkout/address', [CheckoutController::class, 'address']);
            Route::get('checkout/coupons', [CheckoutController::class, 'coupons']);
            Route::post('checkout/coupons/apply', [CheckoutController::class, 'couponsApply']);
            Route::post('checkout/coupons/remove', [CheckoutController::class, 'couponsRemove']);
            Route::post('checkout/complete', [CheckoutController::class, 'complete']);
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

            Route::get('orders', [OrderController::class, 'index']);
            Route::get('orders/{order}', [OrderController::class, 'show']);
            Route::get('orders/{order}/cancel', [OrderController::class, 'cancel']);
            Route::get('orders/{order}/return', [OrderController::class, 'orderReturn']);
            Route::get('orders/{order}/replace', [OrderController::class, 'replace']);
        });
    });
