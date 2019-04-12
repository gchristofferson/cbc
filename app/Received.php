<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Received extends Model
{
    //
    protected $fillable = [
        'inquiry_id',
        'user_id',
    ];

    public static function userInquiries($user)
    {
        $receivedCollection = Received::where('user_id', $user->id)->get();
        $inquiries = [];
        foreach ($receivedCollection as $received) {
            $inquiry = $received->inquiry;
            $inquiry['read'] = $received->read;
            if ($inquiry->user_id != $user->id) {
                $inquiries[] = $inquiry;
            }
        }
        return $inquiries;
    }

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
