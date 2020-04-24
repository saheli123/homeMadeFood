<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Review;
class FoodItem extends Model
{
    //
    protected $fillable = [
        'name','delivery_time','delivery_end_time','delivery_type','picture', 'detail','slug', 'dish_type','cuisine_type','price',"unit",'user_id'
    ];
    public function reviews()
    {
    	return $this->hasMany(Review::class,'product_id');
    }
    public function cook(){
        return $this->hasOne(\App\User::class,"user_id");
    }
}
