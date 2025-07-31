<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_subcategory_product'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'subcategory_product_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'subcategory_product_name',
        'slug_subcategory_product',
        'subcategory_product_desc',
        'parent_category_product_id',
        'subcategory_product_status',
        'banner',
    ];
}
