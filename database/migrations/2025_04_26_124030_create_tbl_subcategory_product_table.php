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
        Schema::create('tbl_subcategory_product', function (Blueprint $table) {
            $table->increments('subcategory_product_id');
            $table->string('subcategory_product_name');
            $table->string('slug_subcategory_product');
            $table->text('subcategory_product_desc');
            $table->integer('parent_category_product_id');
            $table->string('banner');
            $table->integer('subcategory_product_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_subcategory_product');
    }
};
