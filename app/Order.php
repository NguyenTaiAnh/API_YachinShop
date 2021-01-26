<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //
    use SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'code',
        'discount_id',
        'total',
        'qr_code',
        'user_id',
        'address_id',
        'status',
        'paystatus_id',
        'pay_type_id',
        'shipping_id',
    ];

    public function item()
    {
        return $this->hasMany(Item::class, 'order_id', 'id');
    }

    public function address()
    {
        return $this->hasMany(Addresses_order::class, 'order_id', 'id');
    }

    public function OrderStatus()
    {
        return $this->hasMany(Order_status::class, 'order_id', 'id');
    }

    public function ship()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id', 'id');
    }

    public function paytype()
    {
        return $this->belongsTo(Paytypes::class, 'pay_type_id', 'id');
    }

    public function discount() {
        return $this->belongsTo(Discounts::class, 'discount_id', 'code');
    }

    public function statusName(){
        return $this->belongsTo(OderStatusName::class, 'status', 'id');
    }

}
