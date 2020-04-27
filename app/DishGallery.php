<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishGallery extends Model
{
    //
    protected $table="dish_gallery";
    protected $fillable = [
        'image','dish_id'
    ];
}
