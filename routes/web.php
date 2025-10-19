<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;

// Debug routes
Route::get('/debug-db', function () {
    try {
        DB::connection()->getPdo();
        $tables = DB::select('SHOW TABLES');
        return response()->json([
            'status' => 'connected',
            'database' => DB::connection()->getDatabaseName(),
            'tables' => $tables,
            'env_db_host' => env('DB_HOST'),
            'env_db_database' => env('DB_DATABASE'),
            'env_db_port' => env('DB_PORT')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'env_db_host' => env('DB_HOST'),
            'env_db_database' => env('DB_DATABASE'),
            'env_db_port' => env('DB_PORT'),
            'env_db_username' => env('DB_USERNAME')
        ]);
    }
});

Route::get('/debug-data', function () {
    try {
        $categories = Category::count();
        $products = Product::count();
        
        return response()->json([
            'categories_count' => $categories,
            'products_count' => $products,
            'categories' => Category::all(),
            'products' => Product::limit(5)->get()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ]);
    }
});

// Your main SPA route
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');