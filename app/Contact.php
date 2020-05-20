<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Contact extends Model
{
    use Searchable;
    protected $table = 'contacts';

    protected $fillable = ['phone', 'country', 'state', 'pincode', 'address_line_1', 'address_line_2','latitude','longitude'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function toSearchableArray()
    {
      $array = $this->toArray();

      return array('country' => $array['country'],'state'=> $array['state'],'pincode'=>$array['pincode']);
    }
    public function country(){
        return $this->hasOne(App\Countries::class,'country');
    }
    public function state(){
        return $this->hasOne(App\States::class,'state');
    }
    public function city(){
        return $this->hasOne(App\Cities::class,'city');
    }
}
