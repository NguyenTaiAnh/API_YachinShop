<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addresses_order extends Model
{
    //
    use SoftDeletes;
    protected $table = 'addresses_order';
    protected $fillable = [
        'id',
        'province',
        'district',
        'ward',
        'village',
        'phone',
        'user_id',
        'order_id'
    ];
    public function districts()
    {
        return $this->belongsTo(District::class, 'district', 'districtid');
    }
    public function wards()
    {
        return $this->belongsTo(Ward::class, 'ward', 'wardid');
    }

    public function provinces()
    {
        return $this->belongsTo(Province::class, 'province', 'provinceid');
    }

}
