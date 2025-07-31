<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_brand_product'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'brand_product_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'brand_product_name',
        'slug_brand_product',
        'brand_product_desc',
        'brand_product_status',
        'banner',
    ];
}
