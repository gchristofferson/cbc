<?php

namespace App\Http\Controllers;

use http\Exception\BadConversionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use Redirect;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Subscription;
use App\Discount;

class PaymentController extends Controller
{
    //
    private $_api_context;
    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
        ));
        $this->_api_context->setConfig($paypal_conf['settings']);

    }

    public function payWithpaypal()
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // repeat for each item in cart
        $item1 = new Item();
        $item1->setName('New Subscriptions')
            ->setCurrency('USD')
            ->setQuantity(1)
//            ->setSku("123123") // Similar to `item_number` in Classic API
            ->setPrice(request()->amount);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

//        $details = new Details();
//        $details->setShipping(1.2)
//            ->setTax(1.3)
//            ->setSubtotal(17.50);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(request()->amount);
//            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment for selected subscriptions");
//            ->setInvoiceNumber(uniqid());

//        $baseUrl = getBaseUrl();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(\URL::to('status'))
            ->setCancelUrl(\URL::to('status'));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

//        $request = clone $payment;

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return back();
            } else {
                \Session::put('error', 'Some error occurred, sorry for the inconvenience');
                return back();
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        \Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {

            // redirect to paypal
            return \Redirect::away($redirect_url);
        }



        \Session::put('error', 'Unknown error occured');
        return back();

    }

    public function getPaymentStatus()
    {
        $payment_id = \Session::get('paypal_payment_id');

        \Session::forget('paypal_payment_id');

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            \Session::put('error', 'Payment failed');
            return back();
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            \Session::put('success', 'Payment success');

            // create the subscriptions
            $cart_datas = session()->pull('cart_datas', []);
            foreach ($cart_datas as $cart_data) {
                $state_id = $cart_data['cart_state']->id;
                $subscription = new Subscription();
                $subscription->user_id = auth()->id();
                $subscription->state_id = $state_id;
                if ($cart_data['this_discount_amount'] <= 0) {
                    $discount = new Discount();
                    $discount->state_id = $state_id;
                    $discount->discount = 0;
                    $discount->save();
                    $subscription->discount_id = $discount->id;
                    $subscription->has_discount = false;
                    $subscription->discount_expire_date = now();
                    $subscription->discount_expired = true;
                    $subscription->used = 1;
                } else {
                    $subscription->discount_id = $cart_data['this_state_discount']->id;
                    $subscription->has_discount = true;
                    $subscription->discount_expire_date = now()->addDays($cart_data['this_state_discount']->days_to_expire_discount);
                }
                $subscription->subscription_start_date = strtotime($cart_data['this_start_date']);
                $subscription->subscription_expire_date = strtotime($cart_data['this_expire_date']);
                $subscription->paid = true;
                $subscription->save();
            }

            // empty the cart
            session()->forget('cart_datas');


            return Redirect::to('/subscriptions/create');

        }

        \Session::put('error', 'Payment failed');
        return back();
    }
}
