<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\State;
use App\Helpers\SortHelper;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{


    public function show()
    {
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return redirect('/update-profile#subscribe-cart');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
//        session()->forget('cart_datas');
//        return view('subscriptions.create');

        $data = [];

        // get all states
        $states = State::all()->sortBy('state');


        // get user subscriptions
        $subscriptions = auth()->user()->subscriptions->sortBy('state_id');
        $data['subscriptions'] = $subscriptions;


        $cart_state = '';
//        $msg = '';
//        $success_msg = '';
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

            session()->flash('success', 'Item removed from cart');

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


            session()->flash('error', 'Item already in cart');
//            $msg = 'Item already in cart';

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
                if ($promo_code == $state_discount->promo_code && $promo_code != '' && $promo_code != null) {

                    $code_match = true;
//                    return $promo_code;

                    // remember the cart states discount
                    $this_state_discount = $state_discount;

                }
//                else if (strlen($promo_code) != 0 && $this_state_discount != '') {
//                    return "here";
//                    session()->flash('error', 'Sorry, the code you entered is invalid');
////                    $msg = "Sorry, the code you entered is invalid";
//                }
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
                            $this_start_date = date(strtotime($this_start_date. " + $this_state_discount->days_to_expire_discount days"));
                            $this_start_date = gmdate("Y-m-d\TH:i:s\Z", $this_start_date);
                            $this_expire_date = $this_start_date;
//                            $this_expire_date = $this_start_date->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        session()->flash('success', 'Discount applied!');
                    } else if ($used < $limit) {
                        if ($match == true) {
                            $this_start_date = date(strtotime($this_start_date. ' + 365 days'));
                            $this_start_date = gmdate("Y-m-d\TH:i:s\Z", $this_start_date);
                            $this_expire_date = $this_start_date;
//                            $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays(365)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        session()->flash('success', 'Discount applied!');
                    } else {
                        if ($match == true) {
                            $this_start_date = date(strtotime($this_start_date. ' + 365 days'));
                            $this_start_date = gmdate("Y-m-d\TH:i:s\Z", $this_start_date);
                            $this_expire_date = $this_start_date;
//                            $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays(365)->toDateTimeString();
                        }
                        $this_used = $used;
                        session()->flash('error', "Sorry, you've already used your limit of this discount");
                    }
                }
                // otherwise, apply based on whether expire date is overridden
                else {
                    if ($this_state_discount == 'on') {
                        if ($match == true) {
                            $this_start_date = date(strtotime($this_start_date. " + $this_state_discount->days_to_expire_discount days"));
                            $this_start_date = gmdate("Y-m-d\TH:i:s\Z", $this_start_date);
                            $this_expire_date = $this_start_date;
//                            $this_expire_date = $this_start_date->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays($this_state_discount->days_to_expire_discount)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        session()->flash('success', 'Discount applied!');
                    }
                    else {
                        if ($match == true) {
                            $this_start_date = date(strtotime($this_start_date. ' + 365 days'));
                            $this_start_date = gmdate("Y-m-d\TH:i:s\Z", $this_start_date);
                            $this_expire_date = $this_start_date;
//                            $this_expire_date = $this_start_date->addDays(365)->toDateTimeString();
                        } else {
                            $this_expire_date = now()->addDays(365)->toDateTimeString();
                        }
                        $this_used = 1;
                        $this_discount_amount = $this_state_discount->discount;
                        $this_discount_desc = $this_state_discount->discount_desc;
                        $this_price = $cart_state->price - $this_discount_amount;
                        session()->flash('success', 'Discount applied!');
                    }
                }
                // otherwise promo code is empty or invalid
            } else {
                if ($match == true) {
                    $this_start_date = date(strtotime($this_start_date. ' + 365 days'));
                    $this_start_date = gmdate("Y-m-d\TH:i:s\Z", $this_start_date);
                    $this_expire_date = $this_start_date;
                } else {
                    $this_expire_date = now()->addDays(365)->toDateTimeString();
                }
                if (strlen($promo_code) > 0) {
                    session()->flash('error', 'Sorry, the code you entered is invalid');
                }
            }

            $cart_data['id'] = request()->state;
            $cart_data['cart_state'] = $cart_state;
//            return $this_start_date;
            $t = strtotime($this_start_date);
            $t = date("m-d-Y", $t);
            $cart_data['this_start_date'] = $t;
//            return $cart_data['this_start_date'];
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


            // TODO: add message concatenate so all messages display and aren't overwritten
            session()->flash('success', 'State subscription added to cart!');
            session()->push('cart_datas', $cart_data);
        }





        // add states for select menu
        $data['states'] = $states;



//        return session('cart_states');

//            return session('cart_datas');
//
//
//            return $cart_data;
//        return view('contents.update-profile', $data);
        return redirect('/update-profile')->with(['data' => $data]);
//        return $data;


//        return redirect()->back()->with('data', $data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        // TODO
    }

}
