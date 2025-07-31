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
        Schema::create('tbl_favorite_products', function (Blueprint $table) {
            $table->increments('favorite_product_id');
            $table->integer('customer_id');
            $table->integer('product_id');
            $table->unique(['customer_id','product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_favorite_products');
    }
};
