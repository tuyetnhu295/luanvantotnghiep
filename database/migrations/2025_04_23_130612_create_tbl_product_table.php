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
        Schema::create('tbl_product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->integer('subcategory_product_id');
            $table->integer('brand_product_id');
            $table->string('product_name');
            $table->string('product_code');
            $table->string('slug_product');
            $table->text('product_desc');
            $table->text('product_content');
            $table->string('product_material');
            $table->string('product_price');
            $table->string('product_image');
            $table->integer('product_status');
            $table->integer('total_sold')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_product');
    }
};
