<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_product'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'product_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'subcategory_product_id',
        'brand_product_id',
        'product_name',
        'slug_product',
        'product_desc',
        'product_content',
        'product_material',
        'product_price',
        'product_image',
        'product_status',
        'total_sold'
    ];
}
