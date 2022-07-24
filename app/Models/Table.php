<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name','meal_type','number_of_seats', 'created_at'];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_tables');
    }
}
