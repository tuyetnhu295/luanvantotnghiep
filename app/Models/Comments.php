<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_review'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'review_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'customer_id',
        'product_id',
        'review_text',
        'color',
        'size',
        'status',
    ];
}
