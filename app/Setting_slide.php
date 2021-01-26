<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting_slide extends Model
{
    //
    protected $table = 'setting_slide';
    protected $fillable = ['id','image'];
}
