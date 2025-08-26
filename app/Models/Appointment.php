<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function hasCustomer()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }
	
	 public function hasTimeZone()
    {
        return $this->hasOne(City::class, 'id', 'time_zone');
    }
}