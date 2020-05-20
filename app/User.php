<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyApiEmail;
use Laravel\Scout\Searchable;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable,Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','slug','timezone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function toSearchableArray()
{
  $array = $this->toArray();

  return array('name' => $array['name']);
}
    public function dishes()
    {
        return $this->hasMany(FoodItem::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class);
    }
    public function orders()
    {
        return $this->hasMany(\App\Order::class, "customer_id");
    }
    public function ordersbycustomer()
    {
        return $this->hasManyThrough(\App\OrderProduct::class, \App\FoodItem::class,"user_id","product_id","id");
    }
    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail); // my notification
    }
    public static function getLocationFromLatLng($lat,$lng){
       $googleapiUrl="https://maps.google.com/maps/api/geocode/json?key=AIzaSyAmnhEQb6hHCIWl-diDI_thu4gENFalHyw&latlng=".$lat.",".$lng."&sensor=false";
       $data = file_get_contents($googleapiUrl);
       $data = json_decode($data);
        $add_array  = $data->results;

        $country = "";
        $state = "";
        $city = "";
        $pincode=00000;
        $address=[];
        if(isset($add_array[0])){
            $add_array = $add_array[0];
            $add_array = $add_array->address_components;
        //$address['fullAddress']=isset($add_array->formatted_address)?$add_array->formatted_address:"";
        foreach ($add_array as $key) {
        if($key->types[0] == 'administrative_area_level_2')
        {
            $city = $key->long_name;
        }
        if($key->types[0] == 'administrative_area_level_1')
        {
            $state = $key->long_name;
        }
        if($key->types[0] == 'country')
        {
            $country = $key->short_name;
        }
        if($key->types[0] == 'postal_code')
        {
            $pincode = $key->long_name;
        }
        }
    }
        $address["country"]=$country!=""?(\App\Countries::where("sortname",$country)->count()>0?\App\Countries::where("sortname",$country)->first()->id:0):0;
        $address["city"]=$city!=""?(\App\Cities::where("name",$city)->count()>0?\App\Cities::where("name",$city)->first()->id:0):0;
        $address["state"]=$state!=""?(\App\States::where("name",$state)->count()>0?\App\States::where("name",$state)->first()->id:0):0;
        //$address["pincode"]=$pincode;

        return $address;
        //echo "Country : ".$country." ,State : ".$state." ,City : ".$city;
    }
}
