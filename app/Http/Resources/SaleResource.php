<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_amount' => (float) $this->total_amount,
            'subtotal' => (float) $this->subtotal,
            'tax' => (float) $this->tax,
            'payment_method' => $this->payment_method,
            'customer_name' => $this->customer_name,
            'items_count' => $this->saleItems->count(),
            'items' => $this->saleItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                    'subtotal' => (float) $item->subtotal,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'image' => $item->product->image,
                    ],
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}