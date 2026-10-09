<?php

namespace App\Support;

use App\Models\Product;

class ShareLinks
{
    // Returns an array of ready-made sharing links/captions for the admin to paste into ad platforms.
    public static function forProduct(Product $product): array
    {
        $url = $product->url();
        $caption = "{$product->name} — {$product->formattedPrice()}";
        $fullCaption = "{$caption}\n{$product->short_description}\n{$url}";

        return [
            'url' => $url,
            'caption' => $fullCaption,
            'whatsapp' => 'https://wa.me/?text=' . rawurlencode($fullCaption),
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($url),
            'x' => 'https://twitter.com/intent/tweet?text=' . rawurlencode($caption) . '&url=' . rawurlencode($url),
            'email' => 'mailto:?subject=' . rawurlencode($product->name) . '&body=' . rawurlencode($fullCaption),
            'sms' => 'sms:?body=' . rawurlencode($fullCaption),
            // Instagram and TikTok don't support prefilled share URLs — the admin copies the caption
            // above and pastes it manually into the post/story composer in those apps.
        ];
    }
}
