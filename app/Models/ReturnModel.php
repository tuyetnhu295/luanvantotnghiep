<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    //
    public $timestamps = false;
    protected $table = 'tbl_returns';
    protected $primaryKey = 'return_id';
    protected $fillable = [
        'order_id',
        'return_date',
        'quantity',
        'return_code',
        'reason',
        'status'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function return_item()
    {
        return $this->belongsTo(ReturnItem::class, 'return_id');
    }
}
