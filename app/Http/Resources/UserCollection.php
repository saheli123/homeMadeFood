<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserCollection extends Resource
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
            'id'=> $this->id,
            'name'=> $this->name,
            'email' => $this->email,
            'dishType'=>$this->profile?$this->profile->dish_type:"",

            'image'=>$this->profile && $this->profile->image?url($this->profile->image):url('img/food_default.jpg'),
            'country' => $this->contact?$this->contact->country:NULL,
            'city' => $this->contact?$this->contact->city:NULL,
            'state' => $this->contact?$this->contact->state:NULL,
            'pincode' => $this->contact?$this->contact->pincode:NULL,
            "dishesCount"=>$this->dishes->count(),
            "notification"=>$this->loadMissing('notifications'),
        ];

    }
}
