<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\DashboardController;

Route::apiResource('categories', CategoryController::class);

Route::get('products/search/{query}', [ProductController::class, 'search']);
Route::get('products/low-stock', [ProductController::class, 'lowStock']);
Route::apiResource('products', ProductController::class);

Route::get('sales', [SaleController::class, 'index']);
Route::get('sales/{id}', [SaleController::class, 'show']);
Route::post('sales', [SaleController::class, 'store']);

Route::get('dashboard/stats', [DashboardController::class, 'stats']);