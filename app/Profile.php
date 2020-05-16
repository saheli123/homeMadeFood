<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Profile extends Model
{
    use Searchable;
    protected $table = 'profiles';

    protected $fillable = ['bio', 'facebook', 'twitter','image',"dish_type"];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function toSearchableArray()
    {
      $array = $this->toArray();
         
      return array('bio' => $array['bio']);
    }
}
