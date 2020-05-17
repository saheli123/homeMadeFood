<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    protected $isProfile;
    public function __construct($resource, $isProfile = false)
    {
        $this->isProfile = $isProfile;

        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request,$isProfile=false)
    {
        //dd($isProfile);
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'slug'=>$this->slug,
            'email' => $this->email,
            'timezone'=>$this->timezone,
            'bio'=> $this->profile && $this->profile->bio?$this->profile->bio:"",
            'dishType'=>$this->profile?$this->profile->dish_type:"",
            'url'=>"/viewProfile/".$this->slug,
            'image'=>$this->profile && $this->profile->image?url($this->profile->image):url('img/food_default.jpg'),
            'country' => $this->contact?$this->contact->country:NULL,
            'city' => $this->contact?$this->contact->city:NULL,
            'state' => $this->contact?$this->contact->state:NULL,
            'pincode' => $this->contact?$this->contact->pincode:NULL,
            "dishesCount"=>$this->dishes()->where(
                function ($q){
                    if(!$this->isProfile){
                        $today=date('Y-m-d H:m:s');
                        $q->where('delivery_time', '>=', $today)
                        ->orWhere('delivery_end_time', '>=', $today)
                            ->orWhereNull('delivery_time');
                    }

                })->count(),
            "notification"=>$this->unreadNotifications
        ];

    }
}
