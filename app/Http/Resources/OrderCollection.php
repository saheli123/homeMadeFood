<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderCollection extends Resource
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
            'billing_address'=>$this->billing_address.",".$this->billing_city.",".$this->billing_state.",".$this->billing_country.",".$this->billing_pincode,
            'dishes'=>$this->ordered_dish()->with("dish")->get(),
            'cook'=>\App\User::find($this->ordered_dish()->first()->dish->user_id)->name,
            'status'=>$this->status==0?"Pending":($this->status==1?"Processing":"Deilivered"),
        ];
    }
}
