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
        Schema::create('tbl_category_product', function (Blueprint $table) {
            $table->increments('category_product_id');
            $table->string('category_product_name');
            $table->string('slug_category_product');
            $table->text('category_product_desc');
            $table->integer('category_product_status');
            $table->string('banner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_category_product');
    }
};
