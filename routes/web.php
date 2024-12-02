<?php

use Takshak\Adash\Http\Middleware\GatesMiddleware;
use Illuminate\Support\Facades\Route;
use Takshak\Adash\Http\Middleware\ReferrerMiddleware;
use Takshak\Ashop\Http\Controllers\Admin\Shop\AttributeController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\BrandController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CategoryController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CouponController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductImageController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CartController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\WishlistController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\OrderController;
use Takshak\Ashop\Http\Controllers\Shop\AddressController as ShopAddressController;
use Takshak\Ashop\Http\Controllers\Shop\BrandController as ShopBrandController;
use Takshak\Ashop\Http\Controllers\Shop\CartController as ShopCartController;
use Takshak\Ashop\Http\Controllers\Shop\CategoryController as ShopCategoryController;
use Takshak\Ashop\Http\Controllers\Shop\OrderController as ShopOrderController;
use Takshak\Ashop\Http\Controllers\Shop\ProductController as ShopProductController;
use Takshak\Ashop\Http\Controllers\Shop\UserController as ShopUserController;
use Takshak\Ashop\Http\Controllers\Shop\WishlistController as ShopWishlistController;
use Takshak\Ashop\Http\Controllers\Shop\ShopController;
use Takshak\Ashop\Http\Controllers\Shop\CheckoutController;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', GatesMiddleware::class])
        ->prefix('admin/shop')
        ->name('admin.shop.')
        ->group(function () {
            Route::resource('attributes', AttributeController::class);

            Route::resource('categories', CategoryController::class);
            Route::prefix('categories')
                ->name('categories.')
                ->controller(CategoryController::class)
                ->group(function () {
                    Route::get('details/{category}', 'details')->name('details');
                    Route::post('details/{category}/update', 'detailsUpdate')->name('details.update');
                    Route::get('brands/{category}', 'brands')->name('brands');
                    Route::post('brands/{category}/update', 'brandsUpdate')->name('brands.update');
                    Route::get('status/{category}', 'statusToggle')->name('status');
                    Route::get('featured/{category}', 'featuredToggle')->name('featured');
                    Route::get('is_top/{category}', 'isTopToggle')->name('is_top');
                    Route::get('attributes/{category}', 'attributes')->name('attributes');
                    Route::post('attributes/{category}/update', 'attributesUpdate')->name('attributes.update');
                    Route::get('delete/{category}', 'destroy')->name('destroy');
                });


            Route::resource('brands', BrandController::class)->except(['show']);
            Route::get('brands/status/{brand}', [BrandController::class, 'statusToggle'])->name('brands.status.toggle');
            Route::get('brands/featured/{brand}', [BrandController::class, 'featuredToggle'])->name('brands.featured.toggle');

            Route::resource('products', ProductController::class);
            Route::prefix('products')->name('products.')->group(function () {
                Route::controller(ProductController::class)->group(function () {
                    Route::get('delete/{product}', 'delete')->name('delete');
                    Route::get('detail/{product}', 'detail')->name('detail');
                    Route::post('detail/update/{product}', 'detailUpdate')->name('detail.update');

                    Route::get('attributes/{product}', 'attributes')->name('attributes');
                    Route::post('attributes/update/{product}', 'attributesUpdate')->name('attributes.update');

                    Route::post('selected/delete', 'selectedDelete')->name('selected.delete');
                    Route::post('selected/featured/{value}', 'selectedFeatured')->name('selected.featured');
                    Route::get('copy/{product}', 'copy')->name('copy');

                    Route::get('export/excel', 'exportExcel')->name('export.excel');

                    Route::get('import/sheets', 'import')->name('import.sheets');
                    Route::post('import/sheets', 'importUpdate')->name('import.update');
                });

                Route::controller(ProductImageController::class)->group(function () {
                    Route::get('images/{product}', 'index')->name('images');
                    Route::post('images/store/{product}', 'store')->name('images.store');
                    Route::get('images/delete/{productImage}', 'destroy')->name('images.destroy');
                    Route::post('images/bulk/delete', 'bulkDelete')->name('images.bulk.destroy');
                });
            });

            Route::resource('coupons', CouponController::class);

            Route::get('carts', [CartController::class, 'index'])->name('carts.index');
            Route::get('carts/delete', [CartController::class, 'destroyChecked'])->name('carts.destroy.checked');
            Route::get('carts/{cart}/delete', [CartController::class, 'destroy'])->name('carts.destroy');

            Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
            Route::get('wishlist/delete', [WishlistController::class, 'destroyChecked'])->name('wishlist.destroy.checked');
            Route::get('wishlist/{wishlist}/delete', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}/delete', [OrderController::class, 'destroy'])->name('orders.destroy');
            Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        });

    Route::middleware(ReferrerMiddleware::class)->prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');

        Route::get('categories', [ShopCategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/list', [ShopCategoryController::class, 'list'])->name('categories.list');

        Route::get('brands', [ShopBrandController::class, 'index'])->name('brands.index');

        Route::get('products', [ShopProductController::class, 'index'])->name('products.index');
        Route::get('products/{product:slug}', [ShopProductController::class, 'show'])->name('products.show');
        Route::get('products/reviews/{product:slug}', [ShopProductController::class, 'reviews'])->name('products.reviews');

        Route::prefix('carts')->name('carts.')->group(function () {
            Route::get('/', [ShopCartController::class, 'index'])->name('index');
            Route::put('update/{cart}', [ShopCartController::class, 'update'])->name('update');
            Route::get('store/{product}', [ShopCartController::class, 'store'])->name('store');
            Route::get('delete/{cart}', [ShopCartController::class, 'delete'])->name('delete');
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
                Route::get('dashboard', [ShopUserController::class, 'dashboard'])->name('dashboard');
                Route::get('profile', [ShopUserController::class, 'profile'])->name('profile');
                Route::post('profile', [ShopUserController::class, 'profileUpdate'])->name('profile.update');

                Route::get('orders', [ShopOrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{order}', [ShopOrderController::class, 'show'])->name('orders.show');

                Route::resource('addresses', ShopAddressController::class);
                Route::get('addresses/{address}/make-default', [ShopAddressController::class, 'makeDefault'])
                    ->name('addresses.make-default');

                Route::prefix('wishlist')->name('wishlist.')->group(function () {
                    Route::get('items', [ShopWishlistController::class, 'items'])->name('items.index');
                    Route::get('items/toggle/{product}', [ShopWishlistController::class, 'itemToggle'])->name('items.toggle');
                });
            });
        });
    });
});
