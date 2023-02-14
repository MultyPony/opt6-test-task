<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $orderCount = Order::count();
        return view('orders.index', compact('orderCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('orders.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
//    public function store(OrderRequest $request, Order $order)
    public function store(OrderRequest $request, Order $order)
    {
        $validated = $request->validated();
//        $order->fill($request->all());
//        dd($order->created_at);
        $order->fill($validated);
        $order->save();

        // TODO Добавить товары
        $products = json_decode($validated['products']);
        $sync = [];

        foreach ($products as $product) {
            $sync[$product->id] = ['quantity' => $product->count];
        }
        $order->products()->sync($sync);

        return redirect()->route('orders.edit', [$order])
            ->with('status', 'order-updated');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $products = $order->products()->withPivot('quantity')->get();
        $products = $products->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
                "price" => $item->price,
                "count" => $item->pivot->quantity,
            ];
        });
        $productsArray = $products->toArray();

        return view('orders.edit', compact('order', 'productsArray'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function update(OrderRequest $request, Order $order)
    {
        $validated = $request->validated();
        $order->fill($validated);
        $order->save();

        $products = json_decode($validated['products']);
        $sync = [];

        foreach ($products as $product) {
            $sync[$product->id] = ['quantity' => $product->count];
        }
        $order->products()->sync($sync);

        return redirect()->route('orders.edit', [$order])
            ->with('status', 'order-updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
