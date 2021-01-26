<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OderStatusName extends Model
{
    //
    protected $table = 'order_status_name';
    protected $fillable = [
        'id',
        'name'
    ];
}
