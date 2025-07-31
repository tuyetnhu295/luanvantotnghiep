<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_product_variants'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'variants_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'stock',
        'status'
    ];
}
