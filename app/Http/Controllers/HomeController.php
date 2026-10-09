<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'products' => Product::where('is_active', true)->latest()->get(),
            'settings' => Setting::current(),
        ]);
    }
}
