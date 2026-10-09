<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'short_description', 'description',
        'price', 'old_price', 'currency',
        'stock_status', 'stock_quantity',
        'category_id', 'main_image', 'ad_image',
        'is_active', 'qr_code_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Use the slug in route model binding instead of the numeric id, e.g. /p/{product}
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'related_products',
            'product_id',
            'related_product_id'
        );
    }

    public function formattedPrice(): string
    {
        return number_format((float) $this->price, 0) . ' ' . $this->currency;
    }

    public function url(): string
    {
        $base = rtrim(Setting::current()->site_base_url, '/');
        return "{$base}/p/{$this->slug}";
    }

    public function imageUrl(): string
    {
        return asset('storage/' . $this->main_image);
    }
}
