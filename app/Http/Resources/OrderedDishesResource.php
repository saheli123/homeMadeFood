<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\Resource;

class OrderedDishesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'product_id'=>$this->product_id,
            'picture'=>url($this->dish->picture),
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            "dish"=>$this->dish
        ];
    }
}
