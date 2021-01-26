<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Order_status extends Model
{
    //
    use SoftDeletes;
    protected $table = 'order_statuses';
    protected $fillable = ['id','name','order_id'];

    public function statusName(){
        return $this->belongsTo(OderStatusName::class, 'name', 'id');
    }
}
