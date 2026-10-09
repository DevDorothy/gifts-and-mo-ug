<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('Gifts&Mo Ug');
            $table->string('whatsapp_number');   // digits only, e.g. 256700000000
            $table->string('call_number');       // e.g. +256700000000
            $table->string('sms_number');        // e.g. +256700000000
            $table->string('site_base_url')->default('https://giftsandmo.ug');
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('x_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
