<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FoodItemCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'price'=>$this->price.($this->unit?"/".$this->unit:""),
            'unit'=>$this->unit,
            'user_id'=>$this->user_id,
            'details'=>$this->detail,
            "delivery_type"=>$this->delivery_type,
            'picture'=>$this->picture?url($this->picture):url('img/food_default.jpg'),
            'delivery_time'=>$this->delivery_time,
            'dish_type'=>$this->dish_type,
            'cuisine_type'=>$this->cuisine_type,
            // 'totalPrice' => round((1-($this->discount/100)) * $this->price,2),
            // 'discount' => $this->discount,
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star')/$this->reviews->count(),2) : 'No rating yet',
            'href' => [
               'link' => route('dishes.show',$this->id)
            ]
        ];
    }
}
