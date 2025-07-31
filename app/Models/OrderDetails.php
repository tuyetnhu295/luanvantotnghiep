<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    //
    public $timestamps = false;

    protected $table = 'tbl_order_details';
    protected $primaryKey = 'order_details_id';

    // thêm các fillable nếu muốn
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'product_sales_quantity',
        'product_color',
        'product_size',
    ];
}
