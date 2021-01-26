<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    //
    protected $table = 'ward';
    protected $filltable = [
        'wardid',
        'name',
        'districtid',
    ];
    public function district()
    {
        return $this->belongsTo(District::class, 'districtid',  'districtid');
    }
    public function villages()
    {
        return $this->hasMany(Ward::class, 'wardid', 'wardid');
    }

}
