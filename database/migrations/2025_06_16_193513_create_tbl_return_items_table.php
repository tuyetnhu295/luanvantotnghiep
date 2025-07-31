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
        Schema::create('tbl_return_items', function (Blueprint $table) {
            $table->increments('return_item_id');
            $table->integer('return_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->text('condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_return_items');
    }
};
