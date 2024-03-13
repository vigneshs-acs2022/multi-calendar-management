<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';
    protected $primaryKey = 'id';
    public $timestamps = false; // Assuming there are no timestamp columns in the 'account' table
    protected $fillable = ['auth_type', 'mail', 'access_token','user_id'];
}
