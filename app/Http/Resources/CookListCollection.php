<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CookListCollection extends Resource
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
            'dishType'=>$this->profile && $this->profile->dish_type?$this->profile->dish_type:"",
            'url'=>"/viewProfile/".$this->slug,
            'image'=>$this->profile && $this->profile->image?url($this->profile->image):url('img/food_default.jpg'),
            'country' => $this->contact?$this->contact->country:NULL,
            'city' => $this->contact?$this->contact->city:NULL,
            'state' => $this->contact?$this->contact->state:NULL,
            'pincode' => $this->contact?$this->contact->pincode:NULL,
            'timezone'=>$this->timezone,
            "dishesCount"=>$this->dishes()->where(
                function ($q) {
                    $today=\Carbon\Carbon::now()->setTimezone($this->timezone)->toDateTimeString();
                    $q->where('delivery_time', '>=', $today)
                    ->orWhere('delivery_end_time', '>=', $today)
                        ->orWhereNull('delivery_time');
                })->count(),

        ];


    }
}
