<?php

namespace App;
use App\FoodItem;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'customer', 'star', 'review'
    ];
    public function product()
    {
    	return $this->belongsTo(FoodItem::class);
    }
}
