<?php

use Takshak\Adash\Http\Middleware\GatesMiddleware;
use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Admin\Shop\AttributeController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\BrandController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CategoryController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductImageController;
use Takshak\Ashop\Http\Controllers\Shop\BrandController as ShopBrandController;
use Takshak\Ashop\Http\Controllers\Shop\CategoryController as ShopCategoryController;
use Takshak\Ashop\Http\Controllers\Shop\ProductController as ShopProductController;
use Takshak\Ashop\Http\Controllers\Shop\ShopController;
use Takshak\Ashop\Http\Controllers\Shop\WishlistController as ShopWishlistController;

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
        });

    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');

        Route::get('categories', [ShopCategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/list', [ShopCategoryController::class, 'list'])->name('categories.list');

        Route::get('brands', [ShopBrandController::class, 'index'])->name('brands.index');

        Route::get('products', [ShopProductController::class, 'index'])->name('products.index');
        Route::get('products/{product:slug}', [ShopProductController::class, 'show'])->name('products.show');

        Route::middleware(['auth'])->group(function () {
            Route::get('wishlist/items/toggle/{product}', [ShopWishlistController::class, 'itemToggle'])->name('wishlist.items.toggle');
        });
    });
});
