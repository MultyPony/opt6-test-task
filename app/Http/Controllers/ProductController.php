<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|ProductCollection
     */
    public function index(Request $request, Product $product)
    {
        if ($request->wantsJson()) {
            $orderByColumn = "id";
            $orderByDir = "asc";
            $name = $request->name ?? null;

            $perPage = $request->per_page ?? 10;
            $page = $request->page ?? 1;
            $offset = ($page <= 1 ? 0 : $page - 1) * $perPage;

            if ($request->hasHeader('Format') && $request->header('Format') === "datatables") {
                $offset = $request->start;
                $perPage = $request->length;
                $orderByDir = $request->order[0]['dir'];
                $orderByColumn = $request->columns[$request->order[0]['column']]['data'];
                $name = $request->search['value'];
            }
            $products = $product->limit($perPage)
                ->orderBy($orderByColumn, $orderByDir)
                ->offset($offset);

            if ($name) {
                $products->where('name','like', "%{$name}%");
            }
            return new ProductCollection($products->get());
        }
        $productCount = $product->count();

        return view('products.index', compact('productCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->fill($validated);
        $product->save();

        return redirect()->route('products.index')->with('success', 'Ура!');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function show(Product $product, Request $request)
    {
        if ($request->wantsJson()) {
            return new ProductResource($product);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->fill($validated);
        $product->save();

        return redirect()->route('products.edit', [$product])->with('status', 'profile-updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
