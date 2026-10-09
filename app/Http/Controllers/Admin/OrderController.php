<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('product')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('customer_name', 'like', '%' . $request->search . '%')
                       ->orWhere('customer_phone', 'like', '%' . $request->search . '%')
                       ->orWhere('order_ref', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', ['orders' => $orders]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,confirmed,processing,delivered,cancelled'],
        ]);

        $order->update($data);

        return back()->with('status', "Order {$order->order_ref} marked as {$data['status']}.");
    }
}
