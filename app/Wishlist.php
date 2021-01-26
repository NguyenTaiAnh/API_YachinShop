<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{
    //
    use SoftDeletes;
    protected $table = "wishlist";
    protected $fillable= ['id','product_id','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->hasMany(Product::class,'id','product_id');
    }
}
