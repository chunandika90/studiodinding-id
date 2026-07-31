<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class CustomerGroupModel extends Model
{
    use HasFactory;
    
    protected $table = 'customer_groups';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }

    public function customer() {
        return $this->hasMany(CustomerModel::class, 'customer_group_id');
    }
}
