<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function generate(Product $product)
    {
        if (!auth()->user()->purchasedProducts()->where('product_id', $product->id)->exists()) {
            abort(403);
        }

        $url = Storage::disk('private')->temporaryUrl(
            $product->file_path,
            now()->addMinutes(30),
            ['ResponseContentDisposition' => 'attachment; filename="' . $product->title . '.' . pathinfo($product->file_path, PATHINFO_EXTENSION) . '"']
        );

        return response()->json(['download_url' => $url, 'expires_in_minutes' => 30]);
    }
}