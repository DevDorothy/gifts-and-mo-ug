<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderLinks
{
    public static function whatsapp(Product $product, Setting $settings): string
    {
        $number = preg_replace('/\D/', '', $settings->whatsapp_number); // digits only, no +
        $message = "Hi, I'd like to order:\n"
            . "*{$product->name}*\n"
            . "Price: {$product->formattedPrice()}\n"
            . "Link: {$product->url()}";

        return "https://wa.me/{$number}?text=" . rawurlencode($message);
    }

    public static function call(Setting $settings): string
    {
        return 'tel:' . $settings->call_number;
    }

    // iOS Safari needs `&body=`, Android needs `?body=` — detect from the request's user agent.
    public static function sms(Product $product, Setting $settings, Request $request): string
    {
        $message = "I'd like to order: {$product->name} - {$product->formattedPrice()} - {$product->url()}";
        $encoded = rawurlencode($message);

        $ua = $request->userAgent() ?? '';
        $separator = (stripos($ua, 'iphone') !== false || stripos($ua, 'ipad') !== false) ? '&' : '?';

        return "sms:{$settings->sms_number}{$separator}body={$encoded}";
    }
}
