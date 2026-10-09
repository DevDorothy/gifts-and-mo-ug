<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin login — change this password after your first login.
        AdminUser::updateOrCreate(['username' => 'admin'], [
            'name' => 'Admin',
            'password' => Hash::make('ChangeMe123!'),
        ]);

        Setting::updateOrCreate(['id' => 1], [
            'business_name' => 'Gifts&Mo Ug',
            'whatsapp_number' => '256700000000',   // digits only, no + or spaces
            'call_number' => '+256700000000',
            'sms_number' => '+256700000000',
            'site_base_url' => config('app.url'), // e.g. http://localhost:8000
        ]);

        Product::updateOrCreate(['slug' => 'classic-vintage-watch'], [
            'name' => 'Classic Vintage Watch',
            'short_description' => 'Elegant vintage-style watch, timeless design for men and women.',
            'description' => 'Designed with elegant craftsmanship, this watch combines timeless style, reliable performance, and lasting durability. Perfect for everyday wear or as a thoughtful gift.',
            'price' => 85000,
            'currency' => 'UGX',
            'stock_status' => 'in_stock',
            'main_image' => 'products/sample-watch.jpg', // add this file under storage/app/public/products/
            'is_active' => true,
        ]);
    }
}
