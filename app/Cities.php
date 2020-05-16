<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Cities extends Model
{
    //
    use Searchable;
    protected $fillable = ['name', 'state_id'];
    public function toSearchableArray()
    {
      $array = $this->toArray();
         
      return array('name' => $array['name']);
    }
}
