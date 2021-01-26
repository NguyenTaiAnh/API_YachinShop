<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    //
    use SoftDeletes;
    protected $table = 'attributes';
    protected $fillable = [
        'id',
        'size',
        'color',
        'price',
        'amount',
        'price_sale',
        'product_id',
    ];
}
