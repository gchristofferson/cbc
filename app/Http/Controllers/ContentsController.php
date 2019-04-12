<?php

namespace App\Http\Controllers;

use App\Notification;
use App\State;
use DateTime;
use http\Env\Response;
use Illuminate\Http\Request;
use mysql_xdevapi\Session;
use App\Received;

class ContentsController extends Controller
{
    //
    public function login()
    {
        return view('contents/login');
    }

    public function register()
    {
        return view('contents/register');
    }

    public function forgot()
    {
        return view('contents/forgot-password');
    }

    public function updateProfile()
    {
        $data = [];
        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;


        abort_if($user->rejected == 'on', 403);


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;


        $data['received_inquiries'] = Received::userInquiries(auth()->user());

        // get all states
        $states = State::all()->sortBy('state');


        // get user subscriptions
        $subscriptions = auth()->user()->subscriptions->sortBy('state_id');
        $data['subscriptions'] = $subscriptions;


//        $cart_state = '';
        $msg = '';
        $success_msg = '';

        $data['msg'] = $msg;
        $data['success_msg'] = $success_msg;

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
            } else {
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

//        return sizeof($cities);

        if (sizeof($cities) == 0) {
            $data['cities'][] = "Please add a subscription to your account first";
        }
//        return $data;

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

        // flash promo message for expiring subscription

        // sort the array and then get the last items subscription expire date
        $subscriptions_arr = [];
        foreach ($subscriptions as $subscription) {
            array_push($subscriptions_arr, $subscription);
        }
        usort($subscriptions_arr, array("\App\Helpers\SortHelper", "sortByExpire"));

//        return sizeof($subscriptions_arr);

        if (sizeof($subscriptions_arr) != 0) {
            $expiring_subscription = $subscriptions_arr[sizeof($subscriptions_arr) - 1];

            $expiring_date = time() - strtotime($expiring_subscription['subscription_expire_date']);
            $hours = floor($expiring_date / 3600);
            $days = floor($hours / 24);

            $expiring_state = $expiring_subscription['state']->state;


            // if expiring in 14 days or less, send promo_msg
            if ($days <= 0) {
                $days = abs($days);
                if ($days <= 30) {
                    session()->flash('promo_msg', "You are currently subscribed to $expiring_state for only $days more days!");
                    session()->flash('price', $expiring_subscription['state']->price);
                }
            }
        } else if (!session('success')) {
            session()->flash('success', 'Welcome!  Please complete your profile and add a subscription to start sending and receiving inquiries');
        }
//        return $data['states'];

        return view('contents/update-profile', $data);
    }

    public function dashboard()
    {
        $data = [];
        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;

        if ($user->approved == 'off') {
            session()->flash('error', 'Your account is still pending approval');
            return redirect('/update-profile');
        }

        abort_if($user->rejected == 'on', 403);


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }


        $data['received_inquiries'] = Received::userInquiries(auth()->user());


        // get sent inquiries
        $sent_inquiry_rows = \App\Sent::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

        // get the inquiry id's from each row
        $sent_inquiry_ids = [];
        foreach ($sent_inquiry_rows as $row) {
            array_push($sent_inquiry_ids, $row->inquiry_id);
        }

        // remove duplicates from sent list
        $sent_inquiry_ids = array_unique($sent_inquiry_ids);

        // for each id, get the corresponding inquiry
        $sent_inquiries = [];
        foreach ($sent_inquiry_ids as $id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $id)->get();
            foreach ($sent_inquiry_rows as $row) {
                if ($row->inquiry_id == $id) {
                    $inquiry['sent_id'] = $row->id;
                }
            }
            array_push($sent_inquiries, $inquiry);
        }

//        return $sent_inquiries;

        $data['sent_inquiries'] = $sent_inquiries;

        // get saved inquiries
        $saved_inquiry_rows = \App\Saved::orderBy('created_at', 'desc')->take(4)->where('user_id', $user->id)->get();
//        return $saved_inquiry_rows;

        // get the inquiry id's from each row
        $saved_inquiry_ids = [];
        foreach ($saved_inquiry_rows as $row) {
            array_push($saved_inquiry_ids, $row->inquiry_id);
        }

        // for each id, get the corresponding inquiry
        $saved_inquiries = [];
        foreach ($saved_inquiry_ids as $id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $id)->get();
            foreach ($saved_inquiry_rows as $row) {
                if ($row->inquiry_id == $id) {
                    $inquiry['saved_id'] = $row->id;
                }
            }
            array_push($saved_inquiries, $inquiry);
        }

//        return $sent_inquiries;

        $data['saved_inquiries'] = $saved_inquiries;

//        return $saved_inquiries;

        // TODO: if approved return dashboard, else, return message to blank page and log user out.

        return view('contents/dashboard', $data);
    }


}
