<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfPartnership extends Model
{
     protected $table = 'type_of_partnership'; 
     protected $fillable = ['name', 'max_bookings'];
}
