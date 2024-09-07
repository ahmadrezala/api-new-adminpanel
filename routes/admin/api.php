<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductCategoryController;




Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::apiResource('/brands', BrandController::class);
    Route::Resource('/categories', CategoryController::class)->except('edit');
    Route::apiResource('/attributes', AttributeController::class);
    Route::apiResource('/tags', TagController::class);
    Route::Resource('/products', ProductController::class);
    Route::get('/attributes-category/{category}', [ProductController::class, 'attributesCategory']);
    Route::apiResource('/coupons', CouponController::class);
    Route::apiResource('/banners', BannerController::class);
    Route::put('/products/{product}/images-update', [ProductImageController::class, 'updateImages']);
    Route::delete('/products/{productImage}/images', [ProductImageController::class, 'destroyImage']);
    Route::get('/products/{product}/images', [ProductImageController::class, 'showImages']);
    Route::put('/products/{product}/category-update', [ProductCategoryController::class, 'updateCategory']);
    Route::get('/products/{product}/category', [ProductCategoryController::class, 'productCategory']);
    Route::get('/products-categories', [ProductCategoryController::class, 'categories']);
    Route::get('/dashboard', [DashboardController::class, 'salesChart']);

});



