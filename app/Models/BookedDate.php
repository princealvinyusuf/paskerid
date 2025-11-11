<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedDate extends Model
{
    use HasFactory;

    protected $table = 'booked_date';

    protected $fillable = [
        'kemitraan_id',
        'booked_date',
        'booked_time',
        'booked_date_start',
        'booked_date_finish',
        'booked_time_start',
        'booked_time_finish',
        'type_of_partnership_id',
    ];

    protected $casts = [
        'booked_date' => 'date',
        'booked_date_start' => 'date',
        'booked_date_finish' => 'date',
    ];

    public function kemitraan()
    {
        return $this->belongsTo(Kemitraan::class, 'kemitraan_id');
    }

    public function typeOfPartnership()
    {
        return $this->belongsTo(TypeOfPartnership::class, 'type_of_partnership_id');
    }
}

