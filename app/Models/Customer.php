<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    public $timestamps = false;

    protected $table = 'tbl_customer';
    protected $primaryKey = 'customer_id';

    // thêm các fillable nếu muốn
    protected $fillable = [
        'shipping_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_password',
        'customer_birthday',
        'customer_sex',
        'address',
        'ward',
        'district',
        'city'
    ];
}
