<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FoodItemResource extends Resource
{
    /**
     * Transform the resource(single product) into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price'=>$this->price,
            'unit'=>$this->unit,
            'user_id'=>$this->user_id,
            'details'=>$this->detail,
            'picture'=>url($this->picture),
            'delivery_time'=>$this->delivery_time,
            'delivery_end_time'=>$this->delivery_end_time,
            "delivery_type"=>$this->delivery_type,
            'dish_type'=>$this->dish_type,
            'cuisine_type'=>$this->cuisine_type,
            // 'stock' => $this->stock == 0 ? 'Out of stock' : $this->stock,
            // 'discount' => $this->discount,
            // 'totalPrice' => round((1-($this->discount/100)) * $this->price,2),
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star')/$this->reviews->count(),2) : 'No rating yet',
            // 'href' => [
            //    'reviews' => route('reviews.index',$this->id)
            // ]
        ];
    }
}
