<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = SubscriptionPlan::all();

        return Inertia::render(
            'User/Dashboard/Subscription/Index',
            compact('subscriptions')
        );
    }

    public function subscribe(SubscriptionPlan $plan)
    {
        $data = [
            'user_id' => auth()->id(),
            'subscription_plan_id' => $plan->id,
            'price' => $plan->price,
            'expired_date' => now()->addMonths($plan->active_period_in_months),
            'payment_status' => 'paid'
        ];

        UserSubscription::create($data);

        return redirect(route('user.dashboard.index'));
    }
}
