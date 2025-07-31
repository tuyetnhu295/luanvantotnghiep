<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_discount_coupon'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'coupon_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'coupon_code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_value',
        'customer_type',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status'
    ];
}
