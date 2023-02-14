<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->hasHeader('Format') && $request->header('Format') === "datatables") {
            $count = Product::count();
            return [
                "draw" => intval($request->draw),
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $this->collection->map(function ($item) {
                    $item->link_asd = route('products.edit', [$item->id]);
                    return $item;
                })
            ];
        }
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request): array
    {
        if (!$request->hasHeader('Format') && $request->header('Format') !== "datatables") {
            $perPage = $request->per_page ?? 10;

            return [
                'page' => intval($request->page ?? 1),
                'pages' => intval(Product::count() / $perPage),
                'count' => $this->collection->count()
            ];
        }
        return [];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        if ($request->hasHeader('Format') && $request->header('Format') === "datatables") {
            $response->header('Format', 'datatables');
        }
    }
}
