<?php

use Illuminate\Support\Facades\Route;
use Takshak\Adash\Http\Middleware\ReferrerMiddleware;
use Takshak\Ashop\Http\Controllers\Shop\AddressController;
use Takshak\Ashop\Http\Controllers\Shop\BrandController;
use Takshak\Ashop\Http\Controllers\Shop\CartController;
use Takshak\Ashop\Http\Controllers\Shop\CategoryController;
use Takshak\Ashop\Http\Controllers\Shop\OrderController;
use Takshak\Ashop\Http\Controllers\Shop\UserController;
use Takshak\Ashop\Http\Controllers\Shop\WishlistController;
use Takshak\Ashop\Http\Controllers\Shop\ShopController;
use Takshak\Ashop\Http\Controllers\Shop\CheckoutController;
use Takshak\Ashop\Http\Controllers\Shop\ProductController;

Route::middleware('web')->group(function () {
    Route::middleware(ReferrerMiddleware::class)->prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/list', [CategoryController::class, 'list'])->name('categories.list');

        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
        Route::get('products/reviews/{product:slug}', [ProductController::class, 'reviews'])->name('products.reviews');

        Route::prefix('carts')->name('carts.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::put('update/{cart}', [CartController::class, 'update'])->name('update');
            Route::get('store/{product}', [CartController::class, 'store'])->name('store');
            Route::get('delete/{cart}', [CartController::class, 'delete'])->name('delete');
        });

        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', [CheckoutController::class, 'index'])->name('index');
            Route::post('address', [CheckoutController::class, 'address'])->name('address');
            Route::get('summary', [CheckoutController::class, 'summary'])->name('summary');
            Route::post('coupon', [CheckoutController::class, 'coupon'])->name('coupon');
            Route::get('coupon/remove', [CheckoutController::class, 'couponRemove'])->name('coupon.remove');
            Route::post('payment', [CheckoutController::class, 'payment'])->name('payment');
            Route::get('place-order', [CheckoutController::class, 'placeOrder'])->name('place.order');
            Route::get('confirmation/{orderId?}', [CheckoutController::class, 'confirmation'])->name('confirmation');
        });

        Route::middleware(['auth'])->group(function () {
            Route::prefix('user')->name('user.')->group(function () {
                Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
                Route::get('profile', [UserController::class, 'profile'])->name('profile');
                Route::post('profile', [UserController::class, 'profileUpdate'])->name('profile.update');

                Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
                Route::get('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
                Route::get('orders/{order}/return', [OrderController::class, 'orderReturn'])->name('orders.return');
                Route::get('orders/{order}/replace', [OrderController::class, 'replace'])->name('orders.replace');
                Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

                Route::resource('addresses', AddressController::class);
                Route::get('addresses/{address}/make-default', [AddressController::class, 'makeDefault'])
                    ->name('addresses.make-default');

                Route::prefix('wishlist')->name('wishlist.')->group(function () {
                    Route::get('items', [WishlistController::class, 'items'])->name('items.index');
                    Route::get('items/toggle/{product}', [WishlistController::class, 'itemToggle'])->name('items.toggle');
                });
            });
        });
    });
});
