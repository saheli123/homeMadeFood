<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class States extends Model
{
    //
    use Searchable;
    protected $fillable = ['name', 'country_id'];
    public function toSearchableArray()
    {
      $array = $this->toArray();
         
      return array('name' => $array['name']);
    }
}
