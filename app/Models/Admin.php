<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    public $timestamps = false;

    protected $table = 'tbl_admin';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'admin_email',
        'admin_password',
        'admin_name',
        'admin_phone',
        'admin_role',
        'status',
    ];

    protected $hidden = ['admin_password'];

    public function getAuthPassword()
    {
        return $this->admin_password;
    }
}
