<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class CustomerModel extends Model
{
    use HasFactory;
    
    protected $table = 'customers';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }

    public function sales() {
        return $this->belongsTo(UserModel::class, 'user_sales_id');
    }

    public function vendor() {
        return $this->belongsTo(VendorModel::class, 'vendor_id');
    }

    public function inventories() {
        return $this->hasMany(CustomerInventoryModel::class, 'customer_id');
    }
}
