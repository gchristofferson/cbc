<?php

namespace App\Http\Controllers;

use App\Notification;
use App\State;
use DateTime;
use http\Env\Response;
use Illuminate\Http\Request;
use mysql_xdevapi\Session;

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

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }
//        return $received_inquiries;

        $data['received_inquiries'] = $received_inquiries;

        // get all states
        $states = State::all()->sortBy('state');


        // get user subscriptions
        $subscriptions = auth()->user()->subscriptions->sortBy('state_id');
        $data['subscriptions'] = $subscriptions;


        $cart_state = '';
        $msg = '';
        $success_msg = '';
        $match = false;



        // get the state object for the requested state
        foreach ($states as $state) {
            if ($state->id == request()->state) {
                $cart_state = $state;
            }
        }

        // if we don't have a session 'cart_states', add it
        if (! session('cart_datas')) {
            session()->put('cart_datas', []);
        }

        // if user is deleting item from cart
        if (request()->has('delete_btn')) {

            // get the cart item that corresponds to the value sent by delete_btn
            $item_to_delete = request()->delete_btn;

            // delete the item from the session cart_states array
            $cart_datas = session()->pull('cart_datas', []); // Second argument is a default value

            array_splice($cart_datas, $item_to_delete, 1);

            // add the new cart_states back to session
            session()->put('cart_datas', $cart_datas);

        }

        // check if the requested state subscription is already in the cart
        $cart_datas = session()->pull('cart_datas', []);
        session()->put('cart_datas', $cart_datas);

        $cart_states = [];
        foreach ($cart_datas as $cart_data) {
            unset($cart_data['cart_state']['discounts']);
            array_push($cart_states, $cart_data['cart_state']);
        }



        // if item is already in the cart, flash a message
        if ($cart_state != '' && in_array($cart_state, $cart_states)) {


            $msg = 'Item already in cart';

        }

        // if item is not already in the cart, add it
        if ($cart_state != '' && ! in_array($cart_state, $cart_states)) {


            $cart_data = [];
            // set start date for this subscription
            // first, get the matching subscriptions
            $matching_subscriptions = [];
            foreach ($subscriptions as $subscription) {
                if ($subscription->state_id == request()->state) {
                    array_push($matching_subscriptions, $subscription);
                }
            }
            // sort the array and then get the last items subscription expire date
            usort($matching_subscriptions, array("\App\Helpers\SortHelper", "sortByExpire"));

            $oldest_subscription = sizeof($matching_subscriptions) - 1;
            if ($oldest_subscription >= 0) {
                $this_start_date =  $matching_subscriptions[$oldest_subscription]->subscription_expire_date->addDays(1)->toDateTimeString();
                $match = true;
            } else {
                $this_start_date = now()->toDateTimeString();
            }


            // set expire date for this subscription
            // first check if promo code matches
            $promo_code = request()->promo_code;

            $state_discounts = $cart_state->discounts;

            $code_match = false;

            $this_state_discount = '';



            foreach ($state_discounts as $state_discount) {
                if ($promo_code == $state_discount->promo_code) {

                    $code_match = true;

                    // remember the cart states discount
                    $this_state_discount = $state_discount;

                } else if (strlen($promo_code) != 0 && $this_state_discount != '') {
                    $msg = "Sorry, the code you entered is invalid";
                }
            }



            if ($this_state_discount != '') {
                $limit = $this_state_discount->discount_limit;
            } else {
                $limit = 0;
            }

            $used = $limit;
            $this_used = 0;
            $discount_used = false;
            $this_discount_amount = 0;
            $this_price = $cart_state->price;
            $this_discount_desc = '';

            if ($code_match == true) {

                // check if user has already used this discount
                foreach ($subscriptions as $subscription) {
                    if ($this_state_discount->id == $subscription->discount_id) {

                        // user has used this discount. check limit
                        $used = $subscription->used;
                        $discount_used = true;
                    }
                }


                //apply dates and usage based on whether user already used discount
                if ($discount_used == true) {

                    // discount use is less than limit
                    if ($used < $limit && $this_state_discount == 'on' ) {
                        if ($match == true) {
                            $this_expire_date = $this_start_date->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        $success_msg = "Discount applied!";
                    } else if ($used < $limit) {
                        if ($match == true) {
                            $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays(365)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        $success_msg = "Discount applied!";
                    } else {
                        if ($match == true) {
                            $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays(365)->toDateTimeString();
                        }
                        $this_used = $used;
                        $success_msg = "Sorry, you've already used your limit of this discount";
                    }
                }
                // otherwise, apply based on whether expire date is overridden
                else {
                    if ($this_state_discount == 'on') {
                        if ($match == true) {
                            $this_expire_date = $this_start_date->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        $success_msg = "Discount applied!";
                    }
                    else {
                        if ($match == true) {
                            $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays(365)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        $success_msg = "Discount applied!";
                    }
                }
                // otherwise promo code is empty or invalid
            } else {
                if ($match == true) {
                    $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                } else {
                    $this_expire_date = now()->addDays(365)->toDateTimeString();
                }
                if (strlen($promo_code) > 0) {
                    $msg = 'Sorry, the code you entered is invalid';
                }
            }

            $cart_data['id'] = request()->state;
            $cart_data['cart_state'] = $cart_state;
            $t = strtotime($this_start_date);
            $t = date("m-d-Y", $t);
            $cart_data['this_start_date'] = $t;
            $cart_data['this_state_discount'] = $this_state_discount;
            $t = strtotime($this_expire_date);
            $t = date("m-d-Y", $t);
            $cart_data['this_expire_date'] = $t;
            $cart_data['this_used'] = $this_used;
//            $cart_data['msg'] = $msg;
//            $cart_data['success_msg'] = $success_msg;

            // set price for this subscription
            $cart_data['this_discount_amount'] = $this_discount_amount;
            $cart_data['this_discount_desc'] = $this_discount_desc;
            $cart_data['this_price'] = $this_price;


//            session()->flash($msg);
            session()->push('cart_datas', $cart_data);
        }





        // add states for select menu
        $data['states'] = $states;

        // add message
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
            if($days <= 0) {
                $days = abs($days);
                if ($days <= 30) {
                    session()->flash('promo_msg', "You are currently subscribed to $expiring_state for only $days more days!");
                    session()->flash('price', $expiring_subscription['state']->price);
                }
            }
        } else if (! session('success')) {
            session()->flash('success', 'Welcome!  Please complete your profile and add a subscription to start sending and receiving inquiries');
        }

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

        if ($user->approved == 'off' ) {
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


        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }

        //echo "<pre>";
        //print_r(array_slice($received_inquiries, 0, 4));
        //echo "</pre>";
        //die();

        $data['received_inquiries'] = $received_inquiries;

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
        foreach($sent_inquiry_ids as $id) {
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
        foreach($saved_inquiry_ids as $id) {
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
