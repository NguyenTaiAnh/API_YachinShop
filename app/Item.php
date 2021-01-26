<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    use SoftDeletes;
    protected $table = 'items';
    protected $fillable = [
        'id',
        'product_id',
        'amount',
        'price',
        'price_sale',
        'order_id'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
}
