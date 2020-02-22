<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Review;
class FoodItem extends Model
{
    //
    protected $fillable = [
        'name','delivery_time','picture', 'detail','slug', 'dish_type','cuisine_type','price','user_id'
    ];
    public function reviews()
    {
    	return $this->hasMany(Review::class,'product_id');
    }
}
