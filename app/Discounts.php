<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    //
    use SoftDeletes;
    protected $table = "discounts";
    protected $fillable = ['id', 'code','status', 'percent', 'time_start', 'time_end'];

}
