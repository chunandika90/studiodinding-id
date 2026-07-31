<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class VendorModel extends Model
{
    use HasFactory;
    
    protected $table = 'vendor';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }
}