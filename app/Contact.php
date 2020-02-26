<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = ['phone', 'country', 'state', 'pincode', 'address_line_1', 'address_line_2'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
