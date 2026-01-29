<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => url()->current(),
            ],
            'meta' => [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
            ],
        ];
    }
}