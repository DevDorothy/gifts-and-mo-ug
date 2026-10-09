<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Support\OrderLinks;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Route: GET /p/{product}   (Laravel resolves {product} by slug — see Product::getRouteKeyName)
    public function show(Product $product, Request $request)
    {
        abort_unless($product->is_active, 404);

        $settings = Setting::current();

        return view('products.show', [
            'product' => $product,
            'settings' => $settings,
            'whatsappLink' => OrderLinks::whatsapp($product, $settings),
            'callLink' => OrderLinks::call($settings),
            'smsLink' => OrderLinks::sms($product, $settings, $request),
            'related' => $product->relatedProducts()->where('is_active', true)->get(),
        ]);
    }
}
