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
        Schema::create('tbl_returns', function (Blueprint $table) {
            $table->increments('return_id');
            $table->integer('order_id');
            $table->string('return_code')->unique();
            $table->date('return_date');
            $table->integer('quantity');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_returns');
    }
};
