<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('saleItems.product')->latest();

        if ($request->has('filter')) {
            $filter = $request->filter;
            if ($filter === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($filter === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'month') {
                $query->whereMonth('created_at', now()->month);
            }
        }

        $sales = $query->paginate(15);
        return SaleResource::collection($sales);
    }

    public function show($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return new SaleResource($sale);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card',
            'customer_name' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock_quantity}");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal
                ];
            }

            $tax = $subtotal * 0.10;
            $totalAmount = $subtotal + $tax;

            $sale = Sale::create([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'customer_name' => $validated['customer_name'] ?? null
            ]);

            foreach ($itemsData as $itemData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $itemData['subtotal']
                ]);

                $itemData['product']->decrement('stock_quantity', $itemData['quantity']);
            }

            DB::commit();

            return new SaleResource($sale->load('saleItems.product'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}