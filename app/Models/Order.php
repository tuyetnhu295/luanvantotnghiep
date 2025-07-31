<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetails;
class Order extends Model
{
    //
    public $timestamps = false;

    protected $table = 'tbl_order';
    protected $primaryKey = 'order_id';

    // thêm các fillable nếu muốn
    protected $fillable = [
        'customer_id',
        'payment_id',
        'coupon_id',
        'delivery_id',
        'admin_id',
        'order_total',
        'order_status',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_address',
        'shipping_city',
        'shipping_district',
        'shipping_ward',
        'shipping_note',
        'shipping_method',
        'shipping_fee',
        'order_code'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

}
