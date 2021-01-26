<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device_token extends Model
{
    //
    protected $table = 'device_token';
    protected $fillable =[
        'id',
        'token',
        'created_at',
        'updated_at'
    ];

}
