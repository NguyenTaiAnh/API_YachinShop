<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //
    use SoftDeletes;
    protected $table = 'categories';
    protected $fillable = [
        'id',
        'name',
        'image',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    public function product(){
        return $this->hasMany(Product::class,'category_id','id');
    }

}
