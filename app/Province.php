<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = 'province';
    protected $filltable = [
        'provinceid',
        'name',

    ];
    public function districts()
    {
        return $this->hasMany(District::class, 'provinceid', 'provinceid');
    }

}
