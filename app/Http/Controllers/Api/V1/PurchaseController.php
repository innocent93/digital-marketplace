<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\PurchaseMade;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function checkout(Product $product)
    {
        $purchase = Purchase::firstOrCreate(
            ['customer_id' => auth()->id(), 'product_id' => $product->id],
            ['purchased_at' => now()]
        );

        if ($purchase->wasRecentlyCreated) {
            PurchaseMade::dispatch($purchase);
            return response()->json(['message' => 'Purchase successful'], 201);
        }

        return response()->json(['message' => 'Already purchased'], 200);
    }

    public function library()
    {
        return new ProductCollection(auth()->user()->purchasedProducts()->with('category')->paginate(15));
    }
}