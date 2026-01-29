<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class PublicProductController extends Controller
{
    public function index()
    {
        $query = Product::query()
            ->when(request('category'), fn($q, $c) => $q->where('category_id', $c))
            ->when(request('sort_price'), fn($q, $dir) => $q->orderBy('price', $dir === 'desc' ? 'desc' : 'asc'))
            ->with('category');

        return new ProductCollection($query->paginate(15));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }
}