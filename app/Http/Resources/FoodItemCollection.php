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
            'name' => $this->name,
            'price'=>$this->price,
            'details'=>$this->detail,
            'picture'=>url($this->picture),
            'delivery_time'=>$this->delivery_time,
            'dish_type'=>$this->dish_type,
            'cuisine_type'=>$this->cuisine_type,
            // 'totalPrice' => round((1-($this->discount/100)) * $this->price,2),
            // 'discount' => $this->discount,
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star')/$this->reviews->count(),2) : 'No rating yet',
            'href' => [
               'link' => route('products.show',$this->id)
            ]
        ];
    }
}
