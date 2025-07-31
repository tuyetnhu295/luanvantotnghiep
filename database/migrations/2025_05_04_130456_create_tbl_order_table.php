<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->increments('order_id');
            $table->string('order_code');
            $table->integer('customer_id');
            $table->integer('payment_id');
            $table->integer('coupon_id')->nullable();
            $table->integer('delivery_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->string('order_total');
            $table->string('order_status');
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_email')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_district');
            $table->string('shipping_ward');
            $table->text('shipping_note')->nullable();
            $table->enum('shipping_method', ['internal', 'fast', 'free'])->default('internal');
            $table->float('shipping_fee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order');
    }
};
