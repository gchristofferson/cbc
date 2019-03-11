<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
        'state_id',
        'user_id',
        'paid',
        'has_discount',
        'discount_expire_date',
        'discount_expired',
        'used',
        'subscription_start_date',
        'subscription_expire_date',
    ];

    protected $dates = [
        'discount_expire_date',
        'subscription_start_date',
        'subscription_expire_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
