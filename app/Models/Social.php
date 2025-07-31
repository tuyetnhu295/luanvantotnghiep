<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    //
    public $timestamps = false;

    protected $table = 'tbl_social';

    protected $fillable = ['provider_user_id', 'provider', 'user'];
    protected $primaryKey = 'user_id';

    public function login()
    {
        return $this->belongsTo(Customer::class,'user');
    }
}
