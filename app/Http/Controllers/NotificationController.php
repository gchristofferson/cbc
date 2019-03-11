<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use Mockery\Matcher\Not;

class NotificationController extends Controller
{

    public function index()
    {
        abort(404);
    }


    public function show()
    {
        abort(404);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = [];
        $msg = '';
        $success_msg = '';

        // if post
        if (request()->isMethod('post')) {
            request()->validate([
                'city' => 'required',
            ]);
            $city = request()->city;

            // check if user already has this notification
            $current_notifications = Notification::all()->where('user_id', auth()->id());
            $haystack = [];
            foreach ($current_notifications as $current_notification) {
                array_push($haystack, $current_notification->city_id);
            }

            if (in_array($city, $haystack)) {
                $msg = "You already added this notification.";
            }  else {
                $notification = new Notification();
                $notification->user_id = auth()->id();
                $notification->city_id = request()->city;
                $notification->notify = true;
                $notification->save();
                $success_msg = 'Notification added!';
            }
        }




        // get subscribed to cities for dropdown
        $subscriptions = auth()->user()->subscriptions->sortBy('state_id');
        $cities = [];
        foreach ($subscriptions as $subscription) {
            $data['states'][] = $subscription->state;
            foreach ($subscription->state->cities as $city) {
                array_push($cities, $city);
                $data['cities'][] = $city;
            }
        }

        // for each city, push notification to array
        $notifications = [];
        foreach ($cities as $city) {
            $city_notifications = $city->notifications;
            foreach ($city_notifications as $city_notification) {
                if ($city_notification->user_id == auth()->id()) {
                    array_push($notifications, $city_notification);
                }
            }
        }
        $data['notifications'] = $notifications;
        $data['msg'] = $msg;
        $data['success_msg'] = $success_msg;




//        return $notifications;
        session()->flash('success', 'Notification settings updated.');
        return redirect('/update-profile')->with(['data' => $data]);
//        return view('notifications.create', $data);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
        //

        $notification->delete();
        session()->flash('success', 'Notification settings updated');

        return back();
    }
}
