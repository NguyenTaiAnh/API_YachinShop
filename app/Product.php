<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $table = "products";
    protected $fillable = [
        'id',
        'name',
        'description',
        'category_id',
        'user_id',
    ];

    public function attributes()
    {
        return $this->hasMany(Attributes::class, 'product_id', 'id');
    }

    public function image()
    {
        return $this->hasMany(Image::class, 'product_id', 'id');
    }

    public function category()
    {
        return $this->hasMany(Category::class, 'id', 'category_id');
    }
}
