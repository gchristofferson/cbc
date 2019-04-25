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
        $statearray = json_decode($request->stateIds);
        try {
            $this->subscribe($statearray, $request->stripeToken, $request["coupon"]);
            session()->flash('success', 'Subscription successful!.');
        } catch (\Stripe\Error\InvalidRequest $e) {
            session()->flash('error', "Something went wrong, try again");
        } catch (\BadMethodCallException $e) {
            session()->flash('error', "Something went wrong, try again");
        } catch (\Stripe\Error\Card $e) {
            session()->flash('error', "Payment method declined, please try again.");
        } catch (\Exception $e) {
            session()->flash('error', "Something went wrong, try again");
        }
        return redirect('/subscriptions');
        // abort($result);
    }

    private function subscribe($stateIds, $stripe_token, $coupon = null)
    {
        $user = Auth::user();
        $statesToSubscribe = State::WhereIn('id', $stateIds)->get();
        if ($statesToSubscribe->count() !== sizeof($stateIds)) {
            throw new \BadMethodCallException("Mismatch");
        }
        $stripeIds = [];
        foreach ($statesToSubscribe as $state) {
            if (Auth::user()->isSubscribed($state->id)) {
                throw new \BadMethodCallException("User already subscribed");
//                return 400;
            }
            if ($state->stripe_sub_id == null) {
                throw new \BadMethodCallException("States not found");
//                return 500;
            }
            $stripeIds[] = $state->stripe_sub_id;
        }
        //Check coupon
        if (!empty($coupon) && $this->checkStripeCouponP($coupon) == -1) {
            throw new \Exception("coupon not found " . $coupon);
//            return 400;
        }
        if ($user->stripe_customer_id === null) return 500;

        $stripe_cus_id = $user->stripe_customer_id;

        \Stripe\Customer::update(
            $stripe_cus_id,
            [
                'source' => $stripe_token
            ]
        );

        $subscription_data = $this->createStripeSubscription($stripeIds, $user->stripe_customer_id, $coupon);


        //Subscribe
        foreach ($statesToSubscribe as $state) {
            $state->subscribe(
                $user,
                $subscription_data->latest_invoice,
                new \DateTime("@$subscription_data->current_period_start"),
                new \DateTime("@$subscription_data->current_period_end")
            );
        }
    }

    private function checkStripeCouponP($coupon)
    {
        try {
            $response = \Stripe\Coupon::retrieve($coupon);
        } catch (\Stripe\Error\InvalidRequest $r) {
            return -1;
        }
        return $response->percent_off;
    }

    private function createStripeSubscription($sub_items, $user_id, $coupon = null)
    {
        $items = [];
        foreach ($sub_items as $sub_id) {
            $items[] = ['plan' => $sub_id];
        }

        $subscriptionData = [
            'customer' => $user_id,
            'items' => $items
        ];

        if (!empty($coupon)) {
            $subscriptionData['coupon'] = $coupon;
        }

        $subscription = \Stripe\Subscription::create($subscriptionData);

        return (object)[
            "latest_invoice" => $subscription->latest_invoice,
            "current_period_end" => $subscription->current_period_end,
            "current_period_start" => $subscription->current_period_start
        ];
    }

    public function checkStripeCoupon($coupon)
    {
        $rcoupon = $this->checkStripeCouponP($coupon);
        if ($rcoupon == -1) {
            return response("notfound", 404);
        } else {
            return response()->json($rcoupon);
        }
    }

    public function paymentSuccessful(Request $request)
    {
    }

    public function paymentFailed(Request $request)
    {
    }


}
