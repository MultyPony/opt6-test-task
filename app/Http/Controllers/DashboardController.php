<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $orderCount = Order::count();
        $productCount = Product::count();

        return view('dashboard', compact('orderCount', 'productCount'));
    }
}
