<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        $todaySales = Sale::whereDate('created_at', today())
            ->sum('total_amount');

        $todayTransactions = Sale::whereDate('created_at', today())
            ->count();

        $totalProducts = Product::count();

        $lowStockCount = Product::where('stock_quantity', '<', 10)
            ->count();

        $lowStockProducts = Product::where('stock_quantity', '<', 10)
            ->select('id', 'name', 'stock_quantity')
            ->get();

        $recentSales = Sale::latest()
            ->take(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'date' => $sale->created_at->format('Y-m-d H:i:s'),
                    'amount' => (float) $sale->total_amount,
                    'payment_method' => $sale->payment_method,
                    'customer_name' => $sale->customer_name
                ];
            });

        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('sale', function ($query) {
                $query->whereMonth('created_at', now()->month);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->with('product:id,name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'total_quantity' => $item->total_quantity
                ];
            });

        return response()->json([
            'today_sales' => (float) $todaySales,
            'today_transactions' => $todayTransactions,
            'total_products' => $totalProducts,
            'low_stock_count' => $lowStockCount,
            'low_stock_products' => $lowStockProducts,
            'recent_sales' => $recentSales,
            'top_products' => $topProducts
        ]);
    }
}