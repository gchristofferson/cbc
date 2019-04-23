<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Subscriptions extends BaseController
{
    private $stripeKey = "sk_test_vbcdfWod250n4ytU3GQikeuV00J9YZOOhr";

    public function __construct()
    {
        $this->middleware('auth');
        \Stripe\Stripe::setApiKey($this->stripeKey);
    }

    public function index()
    {
        return view('subscriptions.index', $this->getViewData([
            "states" => State::all()
        ]));

    }

    public function store(Request $request)
    {
//        echo "<pre>";
//        print_r($request->all());
//        echo "</pre>";
        $statearray = json_decode($request->stateIds);
        //$statearray[] = 33333;
        $this->subscribe($statearray);
    }

    private function subscribe($stateIds, $coupon = null)
    {
        $user = Auth::user();
        $statesToSubscribe = State::WhereIn('id', $stateIds)->get();
        if ($statesToSubscribe->count() !== sizeof($stateIds)) {
            return 400;
        }
        $stripeIds = [];
        foreach ($statesToSubscribe as $state) {
            if (Auth::user()->isSubscribed($state->id)) {
                return 400;
            }
            if ($state->stripe_sub_id == null) {
                return 500;
            }
            $stripeIds[] = $state->stripe_sub_id;
        }
        //Check coupon
        if ($coupon != null && $this->checkStripeCoupon($coupon)) {
            return 400;
        }
        if ($user->stripe_customer_id === null) return 500;
        $this->createStripeSubscription($stripeIds, $user->stripe_customer_id);
        //Subscribe
    }

    public function checkStripeCoupon($coupon)
    {
        try {
            $response = \Stripe\Coupon::retrieve($coupon);
        } catch (\Stripe\Error\InvalidRequest $r) {
            return false;
        }
        return true;
    }

    private function createStripeSubscription($sub_items, $user_id, $coupon = null)
    {
        $items = [];
        foreach ($sub_items as $sub_id) {
            $items[] = ['plan' => $sub_id];
        }
        $subscription = [
            'customer' => $user_id,
            'items' => $items
        ];
        if ($coupon != null) {
            $subscription['coupon'] = $coupon;
        }
        $subscription = \Stripe\Subscription::create($subscription);
        print_r($subscription);
    }


}
