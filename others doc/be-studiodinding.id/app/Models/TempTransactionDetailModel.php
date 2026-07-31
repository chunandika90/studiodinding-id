<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class TempTransactionDetailModel extends Model
{
    use HasFactory;
    
    protected $table = 'temp_transaction_details';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }

    public function task() {
        return $this->belongsTo(TaskModel::class, 'task_id');
    }

    public function tempTransaction() {
        return $this->belongsTo(TempTransactionModel::class, 'temp_transaction_id');
    }

    public function customerInventory() {
        return $this->belongsTo(CustomerInventoryModel::class, 'customer_inventory_id');
    }
}