<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    //
    use SoftDeletes;
    protected $table = 'shippings';
    protected $fillable = [
      'id',
      'name',
      'cost',
    ];
}
