<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Route: GET /checkout/{product}
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('checkout.show', ['product' => $product]);
    }

    // Route: POST /checkout/{product}
    public function store(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'delivery_address' => ['required', 'string'],
            'delivery_area' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ]);

        $order = Order::create([
            'order_ref' => Order::generateRef(),
            'product_id' => $product->id,
            'quantity' => $data['quantity'],
            'unit_price' => $product->price,
            'total_price' => $product->price * $data['quantity'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'delivery_address' => $data['delivery_address'],
            'delivery_area' => $data['delivery_area'] ?? null,
            'notes' => $data['notes'] ?? null,
            'order_source' => 'site',
            'status' => 'new',
        ]);

        return view('checkout.show', [
            'product' => $product,
            'orderRef' => $order->order_ref,
        ]);
    }
}
