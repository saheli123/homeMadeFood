<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    //
    protected $primary_key = ["order_id", "product_id"];
    protected $table = "orderProduct";
    protected $fillable = [
        'order_id', 'product_id', 'price', 'quantity'
    ];
    public function orders()
    {
        return $this->hasMany(\App\Order::class);
    }
    public function dish(){
        return $this->hasOne(\App\FoodItem::class,"id","product_id");
    }

}
