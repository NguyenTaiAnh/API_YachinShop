<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifi extends Model
{
    //
    use SoftDeletes;
    protected $table = 'notifi';
    protected $fillable = [
      'id',
      'content',
      'url'
    ];
}
