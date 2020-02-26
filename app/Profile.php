<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = ['bio', 'facebook', 'twitter'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
