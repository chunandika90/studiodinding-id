<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class GroupBarangModel extends Model
{
    use HasFactory;
    
    protected $table = 'group_barangs';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }
}