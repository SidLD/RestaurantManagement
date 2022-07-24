<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'status', 'date', 'total', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'booking_product')->withPivot('qty');
    }
    public function tables()
    {
        return $this->belongsToMany(Table::class, 'booking_table');
    }
}
