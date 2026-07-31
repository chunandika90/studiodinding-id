<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class TempTransactionModel extends Model
{
    use HasFactory;
    
    protected $table = 'temp_transactions';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }

    public function customer() {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function details() {
        return $this->hasMany(TempTransactionDetailModel::class, 'temp_transaction_id');
    }
}