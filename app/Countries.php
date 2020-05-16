<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Countries extends Model
{
    //
    use Searchable;
    protected $fillable = ['name', 'sortname','phonecode'];
    public function toSearchableArray()
    {
      $array = $this->toArray();
         
      return array('name' => $array['name'],'sortname'=>$array['sortname']);
    }
}
