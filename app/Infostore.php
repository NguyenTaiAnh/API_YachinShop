<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infostore extends Model
{
    //
    use SoftDeletes;
    protected $table = 'infostore';
    protected $fillable = [
        'id',
        'name',
        'phone',
        'address',
        'logo',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
