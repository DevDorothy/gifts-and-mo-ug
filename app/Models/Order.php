<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_ref', 'product_id', 'quantity', 'unit_price', 'total_price',
        'customer_name', 'customer_phone', 'customer_email',
        'delivery_address', 'delivery_area', 'notes',
        'order_source', 'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Generates a ref like GM-20260926-4821
    public static function generateRef(): string
    {
        do {
            $ref = 'GM-' . now()->format('Ymd') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_ref', $ref)->exists());

        return $ref;
    }
}
