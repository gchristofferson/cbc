<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'license',
        'company_name',
        'company_website',
        'main_market',
        'phone_number',
        'avatar',
        'background_img',
        'agree',
        'approved',
        'rejected',
        'admin',
        'notifications',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function inquiries () {
        return $this->hasMany(Inquiry::class);
    }

    public function notifications () {
        return $this->hasMany(Notification::class);
    }

    public function cities () {
        return $this->hasManyThrough(City::class, Notification::class);
    }

    public function subscriptions () {
        return $this->hasMany(Subscription::class);
    }

    public function states () {
        return $this->hasManyThrough(State::class, Subscription::class);
    }

    public function received_inquiries () {
        return $this->hasManyThrough(Inquiry::class, Received::class);
    }

    public function saved_inquiries () {
        return $this->hasManyThrough(Inquiry::class, Saved::class);
    }

    public function sent_inquiries () {
        return $this->hasManyThrough(Inquiry::class, Sent::class);
    }

    public function isSubscribed($stateId){
        return $this->subscriptions()->where('state_id', $stateId)->count() > 0;
    }

}
