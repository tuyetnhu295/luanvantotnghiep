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
        Schema::create('tbl_product_variants', function (Blueprint $table) {
            $table->increments('variants_id');
            $table->integer('product_id');
            $table->integer('size_id');
            $table->integer('color_id');
            $table->integer('stock');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_product_variants');
    }
};
