<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    protected $table = 'district';
    protected $filltable = [
        'districtid',
        'name',
        'provinceid'
    ];
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceid',  'provinceid');
    }
    public function wards()
    {
        return $this->hasMany(Ward::class, 'districtid', 'districtid');
    }

}
