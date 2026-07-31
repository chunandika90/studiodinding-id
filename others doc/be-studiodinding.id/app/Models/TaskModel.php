<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids;

class TaskModel extends Model
{
    use HasFactory;
    
    protected $table = 'tasks';
	protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
    	if(isset($this->attributes['id'])) return Hashids::encode($this->attributes['id']);
    }
}