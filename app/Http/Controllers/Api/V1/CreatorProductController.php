<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatorProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    public function index()
    {
        return ProductResource::collection(auth()->user()->products()->with('category')->paginate(15));
    }

    public function store(StoreProductRequest $request)
    {
        $product = auth()->user()->products()->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('products', 'private');
            $product->update([
                'file_path' => $path,
                'file_size' => $request->file('file')->getSize(),
                'mime_type' => $request->file('file')->getMimeType(),
            ]);
        }

        return new ProductResource($product->load('category'));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->safe()->except('file'));

        if ($request->hasFile('file')) {
            Storage::disk('private')->delete($product->file_path ?? '');
            $path = $request->file('file')->store('products', 'private');
            $product->update([
                'file_path' => $path,
                'file_size' => $request->file('file')->getSize(),
                'mime_type' => $request->file('file')->getMimeType(),
            ]);
        }

        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product)
    {
        Storage::disk('private')->delete($product->file_path ?? '');
        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }
}