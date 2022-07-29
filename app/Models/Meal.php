<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'created_at'];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'meal_product');
    }
    public function image()
    {
        return $this->hasOne(Image::class);
    }
    
    //In Booking, user can book a meal but I have not created this relation since 
    // Im just going add the products connected to meal table whenever user select that meal
}
