<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    protected $fillable = [
        'discount',
        'discount_desc',
        'days_to_expire_discount',
        'discount_limit',
        'override_subscription_expire',
        'days_to_expire_subscription',
        'promo_code',
    ];

    public function state () {
        return $this->belongsTo(State::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
