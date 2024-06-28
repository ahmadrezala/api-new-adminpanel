<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductCategoryController;




Route::prefix('admin')->group(function () {
    Route::apiResource('/brands', BrandController::class);
    Route::apiResource('/categories', CategoryController::class)->except('show');
    Route::apiResource('/attributes', AttributeController::class)->except('show');
    Route::apiResource('/tags', TagController::class)->except('show');
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/coupons', CouponController::class);
    Route::apiResource('/banners', BannerController::class);
    Route::put('/products/{product}/images-update', [ProductImageController::class, 'updateImages']);
    Route::put('/products/{product}/category-update', [ProductCategoryController::class, 'updateCategory']);

});



