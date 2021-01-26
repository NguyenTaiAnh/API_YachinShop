<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    //
    protected $table = 'village';
    protected $filltable = [
        'villageid',
        'name',
        'wardid'
    ];
    public function Ward()
    {
        return $this->belongsTo(Ward::class, 'wardid',  'wardid');
    }

}
