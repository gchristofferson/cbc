<?php

namespace App\Http\Controllers;

use App\State;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session;

class Subscriptions extends BaseController
{
    private $stripeKey = "sk_test_vbcdfWod250n4ytU3GQikeuV00J9YZOOhr";

    public function __construct()
    {

        \Stripe\Stripe::setApiKey($this->stripeKey);
    }

    public function newAccount(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'license' => ['required', 'string', 'max:255'],
            'agree' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'stripeToken' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator)
                ->withInput();
        }

        $stripeUser = \Stripe\Customer::create([
            "email" => $data['email'],
            "source" => $data['stripeToken'] // obtained with Stripe.js
        ]);

        $duser = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'license' => $data['license'],
            'agree' => $data['agree'],
            'email' => $data['email'],
            'stripe_customer_id' => $stripeUser->id,
            'is_costumer_source_valid' => true,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($duser);

        return redirect('/tutorial?first=true');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->approved != 'on') {
            session()->flash('error', "Your account is not approved, please wait for an administrator to approve your account.");
        }
        return view('subscriptions.index', $this->getViewData([
            "states" => State::all()
        ]));

    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->approved != 'on') {
            return redirect('/subscriptions');
        }
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
            $user->is_costumer_source_valid = false;
            $user->save();
        } catch (\Exception $e) {
            session()->flash('error', "Something went wrong, try again");
        }
        return redirect('/subscriptions');
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
        if (!$user->is_costumer_source_valid) {
            $stripe_cus_id = $user->stripe_customer_id;
            \Stripe\Customer::update(
                $stripe_cus_id,
                [
                    'source' => $stripe_token
                ]
            );
            $user->is_costumer_source_valid = true;
            $user->save();
        }

        $subscription_data = $this->createStripeSubscription($stripeIds, $user->stripe_customer_id, $coupon);


        //Subscribe
        foreach ($statesToSubscribe as $state) {
            $sub_id = $subscription_data->items[$state->stripe_sub_id];
            $state->subscribe(
                $user,
                $subscription_data->latest_invoice,
                $sub_id,
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
        $items = [];
        foreach ($subscription->items->data as $item) {
            $items[$item->plan->id] = $item->subscription;
        }
        return (object)[
            "items" => $items,
            "latest_invoice" => $subscription->latest_invoice,
            "current_period_end" => $subscription->current_period_end,
            "current_period_start" => $subscription->current_period_start
        ];
    }

    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();
        $state = State::find($request->post("id"));
        $current_user_subscription = $state->subscriptions()->where('user_id', $user->id)->firstOrFail();
        $sub_id = $current_user_subscription->sub_id;
        \Stripe\Subscription::update(
            $sub_id,
            [
                'cancel_at_period_end' => true,
            ]
        );
        $state->pause($user);
        session()->flash('success', ', Subscription paused, please reactivate before end date to avoid interruptions!.');
        return redirect('/subscriptions');
    }

    public function restartSubscription(Request $request)
    {
        $user = Auth::user();
        $state = State::find($request->post("id"));
        $current_user_subscription = $state->subscriptions()->where('user_id', $user->id)->firstOrFail();
        $sub_id = $current_user_subscription->sub_id;
        \Stripe\Subscription::update(
            $sub_id,
            [
                'cancel_at_period_end' => false,
            ]
        );
        $state->reactivate($user);
        session()->flash('success', 'Subscription is active');
        return redirect('/subscriptions');
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

}
