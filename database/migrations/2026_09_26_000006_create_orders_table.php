<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_ref', 20)->unique();   // e.g. GM-20260926-0421
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('customer_name', 150);
            $table->string('customer_phone', 30);
            $table->string('customer_email', 150)->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_area', 150)->nullable();
            $table->text('notes')->nullable();
            $table->enum('order_source', ['site', 'whatsapp', 'call', 'sms'])->default('site');
            $table->enum('status', ['new', 'confirmed', 'processing', 'delivered', 'cancelled'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
