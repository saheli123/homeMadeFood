<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'customer_id', 'billing_address', 'billing_country', 'billing_city', 'billing_state', 'billing_pincode', 'billing_phone', 'billing_total', 'status'
    ];
    public function ordered_dish(){
        return $this->hasMany(\App\OrderProduct::class);
    }
}
