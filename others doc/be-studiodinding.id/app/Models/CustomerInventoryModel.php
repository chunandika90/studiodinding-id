<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class CustomerInventoryModel extends Model
{
    use HasFactory;
    
    protected $table = 'customer_inventories';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }

    public function groupBarang() {
        return $this->belongsTo(GroupBarangModel::class, 'group_barang_id');
    }

    public function temp_transaction_detail() {
        return $this->hasMany(TempTransactionDetailModel::class, 'customer_inventory_id');
    }
}