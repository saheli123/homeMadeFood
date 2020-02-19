<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Review;
class FoodItem extends Model
{
    //
    protected $fillable = [
        'name', 'detail', 'delivery_type','price'
    ];
    public function reviews()
    {
    	return $this->hasMany(Review::class,'product_id');
    }
}
