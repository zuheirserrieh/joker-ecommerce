<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\VendorManagementController;
use App\Http\Controllers\Ai\AiToolsController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\StorefrontController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\CustomerController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\SettingsController;
use App\Http\Controllers\Vendor\UploadController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('admin/login', [AdminAuthController::class, 'login']);
    Route::post('vendor/login', [VendorAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('admin/me', [AdminAuthController::class, 'me'])->middleware('admin');
        Route::post('admin/logout', [AdminAuthController::class, 'logout'])->middleware('admin');
        Route::get('vendor/me', [VendorAuthController::class, 'me'])->middleware('vendor');
        Route::post('vendor/logout', [VendorAuthController::class, 'logout'])->middleware('vendor');
    });
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index']);
    Route::apiResource('vendors', VendorManagementController::class);
});

Route::prefix('vendor')->middleware(['auth:sanctum', 'vendor'])->group(function () {
    Route::get('dashboard', [VendorDashboardController::class, 'index']);
    Route::apiResource('categories', CategoryController::class)->except(['create', 'edit']);
    Route::apiResource('products', ProductController::class)->except(['create', 'edit']);
    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
    Route::get('settings', [SettingsController::class, 'show']);
    Route::put('settings', [SettingsController::class, 'update']);
    Route::post('uploads/product-image', [UploadController::class, 'storeProductImage']);

    Route::prefix('ai')->group(function () {
        Route::get('low-stock-alerts', [AiToolsController::class, 'lowStockAlerts']);
        Route::get('sales-forecast', [AiToolsController::class, 'salesForecast']);
        Route::get('earnings-summary', [AiToolsController::class, 'earningsSummary']);
        Route::post('product-description', [AiToolsController::class, 'productDescription']);
    });
});

Route::prefix('store/{vendor:slug}')->group(function () {
    Route::get('/', [StorefrontController::class, 'show']);
    Route::get('products', [StorefrontController::class, 'products']);
    Route::get('products/{product}', [StorefrontController::class, 'product']);
    Route::post('checkout', [CheckoutController::class, 'store']);
    Route::post('payment-intent', [CheckoutController::class, 'paymentIntent']);
});
