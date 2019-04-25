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

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function subscribe($user, $invoice_id, $start_date, $expire_date)
    {
        $current_user_subscription = $this->subscriptions()->where('user_id', $user->id)->get();
        if ($current_user_subscription->count() == 0) {
            $this->subscriptions()->create([
                'user_id' => $user->id,
                'subscription_start_date' => $start_date,
                'subscription_expire_date' => $expire_date,
                'stripe_invoice' => $invoice_id,
            ]);
        }else{
            $current_user_subscription->subscription_start_date  = $start_date;
            $current_user_subscription->subscription_expire_date  = $expire_date;
            $current_user_subscription->stripe_invoice  = $invoice_id;
            $current_user_subscription->save();
        }
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
