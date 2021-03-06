<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    protected $fillable = [
        'state',
        'price',
        'stripe_sub_id'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function subscribe($user, $invoice_id, $sub_id, $start_date, $expire_date)
    {
        $current_user_subscription = $this->subscriptions()->where('user_id', $user->id)->get();
        if ($current_user_subscription->count() == 0) {
            $this->subscriptions()->create([
                'user_id' => $user->id,
                'subscription_start_date' => $start_date,
                'subscription_expire_date' => $expire_date,
                'stripe_invoice' => $invoice_id,
                'sub_id' => $sub_id,
                'renew' => true
            ]);
        }
    }

    public function pause($user){
        $current_user_subscription = $this->subscriptions()->where('user_id', $user->id)->firstOrFail();
        if ($current_user_subscription->count() == 1) {
            $current_user_subscription->renew = false;
            $current_user_subscription->save();
        }
    }

    public function reactivate($user){
        $current_user_subscription = $this->subscriptions()->where('user_id', $user->id)->firstOrFail();
        if ($current_user_subscription->count() == 1) {
            $current_user_subscription->renew = true;
            $current_user_subscription->save();
        }
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
