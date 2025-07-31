<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    //
    public $timestamps = false;
    protected $table = 'tbl_return_items';
    protected $primaryKey = 'return_item_id';
    protected $fillable = [
        'return_id',
        'product_id',
        'quantity',
        'condition'
    ];
}
