<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    //
    public $timestamps = false; // Nếu bạn không muốn Laravel tự cập nhật created_at và updated_at

    protected $table = 'tbl_color_product'; // nếu table không theo chuẩn Laravel (brands)
    protected $primaryKey = 'color_id'; // nếu khóa chính không phải là id

    // thêm các fillable nếu muốn
    protected $fillable = [
        'color_name',
        'color_status'
    ];
}
