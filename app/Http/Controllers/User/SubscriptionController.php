<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Midtrans;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Midtrans\Config::$serverKey = env('MIDTRANS_SERVERKEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = false;
    }

    public function index()
    {
        $subscriptions = SubscriptionPlan::all();
        $userSubscription = null;

        return Inertia::render(
            'User/Dashboard/Subscription/Index',
            compact('subscriptions', 'userSubscription')
        );
    }

    public function subscribe(SubscriptionPlan $plan)
    {
        $data = [
            'user_id' => auth()->id(),
            'subscription_plan_id' => $plan->id,
            'price' => $plan->price,
            // 'expired_date' => now()->addMonths($plan->active_period_in_months),
            'payment_status' => 'pending'
        ];

        $userSubscription = UserSubscription::create($data);

        $params = [
            'transaction_details' => [
                'order_id' => $userSubscription->id . '-' . Str::random(5),
                'gross_amount' => $userSubscription->price
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $userSubscription->update([
            'snap_token' => $snapToken
        ]);

        return Inertia::render(
            'User/Dashboard/Subscription/Index',
            compact('userSubscription')
        );
    }

    public function midtransCallback(Request $request)
    {
        $notif = new Midtrans\Notification();

        $transaction_status = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $transaction_id = explode('-', $notif->order_id)[0];
        $userSubscription = UserSubscription::find($transaction_id);

        if ($transaction_status == 'capture') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'challenge'
                $userSubscription->payment_status = 'pending';
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'success'
                $userSubscription->payment_status = 'paid';
                $userSubscription->expired_date = Carbon::now()->addMonths(
                    (int) $userSubscription->subscriptionPlan->active_period_in_months
                );
            }
        } else if ($transaction_status == 'cancel') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'failure'
                $userSubscription->payment_status = 'failed';
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'failure'
                $userSubscription->payment_status = 'failed';
            }
        } else if ($transaction_status == 'deny') {
            // TODO Set payment status in merchant's database to 'failure'
            $userSubscription->payment_status = 'failed';
        } else if ($transaction_status == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $userSubscription->payment_status = 'paid';
            $userSubscription->expired_date = Carbon::now()->addMonths((int) $userSubscription->subscriptionPlan->active_period_in_months);
        } else if ($transaction_status == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $userSubscription->payment_status = 'pending';
        } else if ($transaction_status == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $userSubscription->payment_status = 'failed';
        }

        $userSubscription->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Payment success'
        ]);
    }
}
