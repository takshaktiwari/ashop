<?php

use Takshak\Adash\Http\Middleware\GatesMiddleware;
use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Admin\Shop\AttributeController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\BrandController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CategoryController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\ProductImageController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\VariationController;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', GatesMiddleware::class])
        ->prefix('admin/shop')
        ->name('admin.shop.')
        ->group(function () {
            Route::resource('attributes', AttributeController::class);
            Route::resource('variations', VariationController::class);
            Route::get('variants/delete/{variant}', [VariationController::class, 'variantsDelete'])->name('variants.delete');

            Route::resource('categories', CategoryController::class);
            Route::prefix('categories')
                ->name('categories.')
                ->controller(CategoryController::class)
                ->group(function () {
                    Route::get('details/{category}', 'details')->name('details');
                    Route::post('details/{category}/update', 'detailsUpdate')->name('details.update');
                    Route::get('status/{category}', 'statusToggle')->name('status');
                    Route::get('featured/{category}', 'featuredToggle')->name('featured');
                    Route::get('is_top/{category}', 'isTopToggle')->name('is_top');
                    Route::get('attributes/{category}', 'attributes')->name('attributes');
                    Route::post('attributes/{category}/update', 'attributesUpdate')->name('attributes.update');
                    Route::get('variations/{category}', 'variations')->name('variations');
                    Route::post('variations/{category}/update', 'variationsUpdate')->name('variations.update');
                    Route::get('delete/{category}', 'destroy')->name('destroy');
                });


            Route::resource('brands', BrandController::class)->except(['show']);
            Route::get('brands/status/{brand}', [BrandController::class, 'statusToggle'])->name('brands.status.toggle');

            Route::resource('products', ProductController::class);
            Route::prefix('products')->name('products.')->group(function () {
                Route::controller(ProductController::class)->group(function () {
                    Route::get('detail/{product}', 'detail')->name('detail');
                    Route::post('detail/update/{product}', 'detailUpdate')->name('detail.update');

                    Route::get('attributes/{product}', 'attributes')->name('attributes');
                    Route::post('attributes/update/{product}', 'attributesUpdate')->name('attributes.update');

                    Route::post('selected/delete', 'selectedDelete')->name('selected.delete');
                    Route::post('selected/featured/{value}', 'selectedFeatured')->name('selected.featured');
                    Route::get('copy/{product}', 'copy')->name('copy');
                    Route::get('export/sheets', 'exportSheets')->name('export.sheets');
                    Route::get('export/sheets/do', 'exportSheetsDo')->name('export.sheets.do');
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
});
