<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'business_name', 'whatsapp_number', 'call_number', 'sms_number',
        'site_base_url', 'facebook_url', 'instagram_url', 'tiktok_url', 'x_url',
    ];

    // Settings is a single-row table; this fetches (or creates) that row.
    public static function current(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'business_name' => 'Gifts&Mo Ug',
            'whatsapp_number' => '256700000000',
            'call_number' => '+256700000000',
            'sms_number' => '+256700000000',
            'site_base_url' => config('app.url'),
        ]);
    }
}
