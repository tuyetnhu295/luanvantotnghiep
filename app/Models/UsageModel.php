<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageModel extends Model
{
    //
     public $timestamps = false;

    protected $table = 'tbl_coupon_usage'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'coupon_usage_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'coupon_id',
        'customer_id'
    ];
}
