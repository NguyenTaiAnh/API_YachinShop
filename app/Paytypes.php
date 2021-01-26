<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Paytypes extends Model
{
    //
    use SoftDeletes;
    protected $table = 'pay_types';
    protected $fillable =['id','payment'];
}
