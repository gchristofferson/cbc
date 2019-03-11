<?php

namespace App\Http\Controllers;

use App\Discount;
use App\State;
use Illuminate\Http\Request;

class DiscountController extends Controller
{

    public function index()
    {
        abort(404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'discount_state_id' => 'required',
            'discount' => 'required',
            'days_to_expire_discount' => 'required',
            'discount_limit' => 'required',
            'promo_code' => 'required',
        ]);



        $discount = new Discount();


        $discount->state_id = request()->discount_state_id;
        $discount->discount = request()->discount;
        $discount->discount_desc = request()->discount_desc;
        $discount->days_to_expire_discount = request()->days_to_expire_discount;
        $discount->discount_limit = request()->discount_limit;
        $discount->override_subscription_expire = request()->override_subscription_expire;
        $discount->promo_code = request()->promo_code;

        $discount->save();

//        return dd(request());

        session()->flash('success', 'Discount Created');
        return back();
    }

    public function create()
    {
        abort(404);
    }

    public function show()
    {
        abort(404);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
        $data['discount'] = $discount;
        $states = State::all();
        $data['states'] = $states;
        $discount_state = $discount->state;
        $data['discount_state'] = $discount_state;

        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;


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

        $data['received_inquiries'] = $received_inquiries;

        if(auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return view('discounts.edit', $data);
        } else {
            return back();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        //
        request()->validate([
            'discount' => 'required',
            'days_to_expire_discount' => 'required',
            'discount_limit' => 'required',
            'promo_code' => 'required',
        ]);

        $state_id = request('discount_state_id');

        $discount->discount = request()->discount;
        $discount->discount_desc = request()->discount_desc;
        $discount->days_to_expire_discount = request()->days_to_expire_discount;
        $discount->discount_limit = request()->discount_limit;
        $discount->override_subscription_expire = request()->override_subscription_expire;
        $discount->promo_code = request()->promo_code;

        $discount->update(request([
            'discount',
            'discount_desc',
            'days_to_expire_discount',
            'discount_limit',
            'override_subscription_expire',
            'promo_code',
        ]));

        session()->flash('success', 'Discount Updated');

        return redirect("/states/$state_id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        //
        $state_id = $discount->state_id;

        // delete notifications for city
        $discount->subscriptions()->delete();

        $discount->delete();

        session()->flash('success', 'Discount and Related Subscriptions Deleted');

        return redirect("/states/$state_id");
    }
}
