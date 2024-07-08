<?php

use Illuminate\Support\Facades\Route;
use Takshak\Ashop\Http\Controllers\Api\CategoryController;

Route::middleware('api')
    ->prefix('api/shop')
    ->group(function () {
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{category}', [CategoryController::class, 'show']);
    });
