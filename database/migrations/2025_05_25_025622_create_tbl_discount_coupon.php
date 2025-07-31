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
        Schema::create('tbl_discount_coupon', function (Blueprint $table) {
            $table->increments('coupon_id');
            $table->string('coupon_code')->unique(); // mã giảm giá, nên unique
            $table->text('description')->nullable(); // mô tả có thể để trống
            // loại giảm giá: theo phần trăm hay số tiền
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            // giá trị giảm (ví dụ: 10% hoặc 50000 VND)
            $table->integer('discount_value');
            // giá trị đơn hàng tối thiểu để áp dụng
            $table->float('min_order_value')->default(0);
            // loại khách hàng: mới, cũ, hoặc tất cả
            $table->enum('customer_type', ['new', 'returning', 'all'])->default('all');
            // thời gian áp dụng
            $table->date('start_date');
            $table->date('end_date');
            // số lần sử dụng tối đa
            $table->integer('usage_limit')->default(1);
            // số lần đã sử dụng
            $table->integer('used_count')->default(0);
            // trạng thái của mã giảm giá
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_discount_coupon');
    }
};
