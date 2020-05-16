<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Review;
use Laravel\Scout\Searchable;

class FoodItem extends Model
{
    //
    use Searchable;
    protected $fillable = [
        'name','delivery_time','delivery_end_time','delivery_type','picture', 'detail','slug', 'dish_type','cuisine_type','price',"unit",'user_id'
    ];
    public function toSearchableArray()
    {
      $array = $this->toArray();

      return array('name' => $array['name'],'dish_type'=>$array['dish_type'],'cuisine_type'=>$array['cuisine_type']);
    }
    public function reviews()
    {
    	return $this->hasMany(Review::class,'product_id');
    }
    public function cook(){
        return $this->hasOne(\App\User::class,"user_id");
    }
    public function images(){
        return $this->hasMany(\App\DishGallery::class,"dish_id");
    }
}
