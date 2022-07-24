<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['src'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
