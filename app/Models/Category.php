<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_category_product'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'category_product_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'category_product_name',
        'slug_category_product',
        'category_product_desc',
        'category_product_status',
        'banner',
    ];
}
