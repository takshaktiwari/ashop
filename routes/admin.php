<?php

use Takshak\Adash\Http\Middleware\GatesMiddleware;
use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Admin\Shop\AttributeController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\BrandController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CategoryController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CouponController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductImageController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CartController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\WishlistController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\OrderController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\OrderUpdateController;

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
                    Route::get('viewed/history', 'viewedHistory')->name('viewed.history');
                    Route::get('viewed/history/bulk/delete', 'viewedHistoryBulkDelete')->name('viewed.history.bulk.delete');
                    Route::get('viewed/history/{productViewed}/delete', 'viewedHistoryDelete')->name('viewed.history.delete');

                    Route::get('delete/{product}', 'delete')->name('delete');
                    Route::get('detail/{product}', 'detail')->name('detail');
                    Route::post('detail/update/{product}', 'detailUpdate')->name('detail.update');

                    Route::get('attributes/{product}', 'attributes')->name('attributes');
                    Route::post('attributes/update/{product}', 'attributesUpdate')->name('attributes.update');

                    Route::get('bulk/delete', 'bulkDelete')->name('bulk.delete');
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
            Route::prefix('coupons/bulk')->name('coupons.bulk.')->group(function () {
                Route::get('delete', [CouponController::class, 'bulkDelete'])->name('delete');
                Route::get('active', [CouponController::class, 'bulkActive'])->name('active');
                Route::get('inactive', [CouponController::class, 'bulkInactive'])->name('inactive');
                Route::get('featured', [CouponController::class, 'bulkFeatured'])->name('featured');
                Route::get('not-featured', [CouponController::class, 'bulkNotFeatured'])->name('not-featured');
            });

            Route::get('carts', [CartController::class, 'index'])->name('carts.index');
            Route::get('carts/delete', [CartController::class, 'destroyChecked'])->name('carts.destroy.checked');
            Route::get('carts/{cart}/delete', [CartController::class, 'destroy'])->name('carts.destroy');

            Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
            Route::get('wishlist/delete', [WishlistController::class, 'destroyChecked'])->name('wishlist.destroy.checked');
            Route::get('wishlist/{wishlist}/delete', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/bulk/delete', [OrderController::class, 'bulkDelete'])->name('orders.bulk.delete');
            Route::get('orders/{order}/delete', [OrderController::class, 'destroy'])->name('orders.destroy')->where('order', '[0-9]+');
            Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
            Route::post('orders/{order}/updates', [OrderUpdateController::class, 'store'])->name('orders.updates.store');
        });
});
