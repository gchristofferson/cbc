<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    //
    protected $fillable = [
        'read',
        'subject',
        'body',
    ];

//    public function cities () {
//        return $this->hasManyThrough(City::class, Sent::class, 'city_id');
//    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deleter()
    {
        $this->documents()->delete();

        // delete all saved and received inquiries
        $this->received_inquiries()->delete();
        $this->saved_inquiries()->delete();
        $this->sent_inquiry()->delete();
        Saved::where('inquiry_id', $this->id)->delete();
        // delete any documents
        $this->documents()->delete();
        $this->delete();
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function received_inquiries()
    {
        return $this->hasMany(Received::class);
    }

    public function saved_inquiries()
    {
        return $this->hasMany(Saved::class);
    }
}
