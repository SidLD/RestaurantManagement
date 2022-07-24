<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name','created_at','image_id'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_product');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_product')->withPivot('qty');
    }
}
