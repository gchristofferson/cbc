<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saved extends Model
{
    //
    protected $fillable = [
        'inquiry_id',
        'user_id',
    ];

    public static function getUserSaveInquires($user)
    {
        $saved_inquiry_rows = \App\Saved::orderBy('created_at', 'desc')->where('user_id', $user->id)->paginate(10);
        // get the inquiry id's from each row
        $saved_inquiry_ids = [];
        foreach ($saved_inquiry_rows as $row) {
            array_push($saved_inquiry_ids, $row->inquiry_id);
        }
        $saved_inquiries = [];
        foreach ($saved_inquiry_ids as $id) {
            $inquiry = \App\Inquiry::where('id', $id)->get();
            foreach ($saved_inquiry_rows as $row) {
                if ($row->inquiry_id == $id) {
                    $inquiry['saved_id'] = $row->id;
                }
            }
            array_push($saved_inquiries, $inquiry);
        }
        return (object) [
            'saved_inquiries' => $saved_inquiries,
            'saved_inquiry_rows' => $saved_inquiry_rows
        ];
    }

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

}
