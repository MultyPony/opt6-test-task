<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->hasHeader('Format') && $request->header('Format') === "datatables") {
            $this->resource->route = route('products.edit', [$this->resource->id]);
        }
        return parent::toArray($request);
    }
}
