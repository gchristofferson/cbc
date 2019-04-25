<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
        'state_id',
        'user_id',
        'subscription_start_date',
        'subscription_expire_date',
        'stripe_invoice'
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
