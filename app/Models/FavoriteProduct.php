<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model
{
    //
    public $timestamps = false;

    protected $table = 'tbl_favorite_products';
    protected $primaryKey = 'favorite_product_id';

    // thêm các fillable nếu muốn
    protected $fillable = [
        'customer_id',
        'product_id',
    ];
}
