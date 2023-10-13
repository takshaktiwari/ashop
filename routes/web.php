<?php

use Takshak\Adash\Http\Middleware\GatesMiddleware;
use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Admin\Shop\AttributeController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\BrandController;
use Takshak\Ashop\Http\Controllers\Admin\Shop\CategoryController;
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
        });
});
