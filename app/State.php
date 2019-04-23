<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    protected $fillable = [
        'state',
        'price',
        'stripe_sub_id',
      ];

    public function cities () {
        return $this->hasMany(City::class);
    }

    public function subscriptions () {
        return $this->hasMany(Subscription::class);
    }

    public function discounts () {
        return $this->hasMany(Discount::class);
    }
}
