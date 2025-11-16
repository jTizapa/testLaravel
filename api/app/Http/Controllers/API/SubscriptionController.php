<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\SubscriptionStatusChanged;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Traits\StructuredLogging;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    use StructuredLogging;

    public function index()
    {
        return Subscription::with(['member','plan'])->latest()->paginate(15);
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $plan = Plan::findOrFail($data['plan_id']);
        $start = isset($data['start_date']) ? Carbon::parse($data['start_date']) : now();
        $end = isset($data['end_date']) ? Carbon::parse($data['end_date']) : (clone $start)->addDays($plan->duration_days);

        $subscription = Subscription::create([
            'member_id' => $data['member_id'],
            'plan_id' => $plan->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'status' => $data['status'] ?? 'active',
            'amount' => $data['amount'] ?? $plan->price,
        ]);

        SubscriptionStatusChanged::dispatch($subscription->load(['member','plan']));
        $this->logInfo('subscription.created', [
            'subscription_id' => $subscription->id,
            'member_id' => $subscription->member_id,
            'plan_id' => $subscription->plan_id,
            'status' => $subscription->status,
        ]);

        return response()->json($subscription->load(['member','plan']), 201);
    }

    public function show(Subscription $subscription)
    {
        return $subscription->load(['member','plan']);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $data = $request->validated();

        $subscription->fill($data)->save();
        SubscriptionStatusChanged::dispatch($subscription->load(['member','plan']));
        $this->logInfo('subscription.updated', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
        ]);

        return response()->json($subscription->load(['member','plan']));
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response()->noContent();
    }
}
