<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'newOrderCount' => Order::where('status', 'new')->count(),
            'recentOrders' => Order::with('product')->latest()->take(5)->get(),
        ]);
    }
}
