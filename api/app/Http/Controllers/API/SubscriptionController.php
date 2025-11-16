<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::with(['member','plan'])->latest()->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => ['required','exists:members,id'],
            'plan_id' => ['required','exists:plans,id'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date'],
            'amount' => ['nullable','numeric','min:0'],
            'status' => ['nullable','in:active,past_due,canceled'],
        ]);

        // Defaults from plan
        $plan = Plan::findOrFail($data['plan_id']);
        $start = isset($data['start_date']) ? \Carbon\Carbon::parse($data['start_date']) : now();
        $end = isset($data['end_date']) ? \Carbon\Carbon::parse($data['end_date']) : (clone $start)->addDays($plan->duration_days);

        $subscription = Subscription::create([
            'member_id' => $data['member_id'],
            'plan_id' => $plan->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'status' => $data['status'] ?? 'active',
            'amount' => $data['amount'] ?? $plan->price,
        ]);

        return response()->json($subscription->load(['member','plan']), 201);
    }

    public function show(Subscription $subscription)
    {
        return $subscription->load(['member','plan']);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'plan_id' => ['sometimes','exists:plans,id'],
            'start_date' => ['sometimes','date'],
            'end_date' => ['sometimes','date'],
            'amount' => ['sometimes','numeric','min:0'],
            'status' => ['sometimes','in:active,past_due,canceled'],
        ]);

        if (isset($data['plan_id'])) {
            $subscription->plan_id = $data['plan_id'];
        }
        if (isset($data['start_date'])) {
            $subscription->start_date = $data['start_date'];
        }
        if (isset($data['end_date'])) {
            $subscription->end_date = $data['end_date'];
        }
        if (isset($data['amount'])) {
            $subscription->amount = $data['amount'];
        }
        if (isset($data['status'])) {
            $subscription->status = $data['status'];
        }

        $subscription->save();
        return response()->json($subscription->load(['member','plan']));
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response()->noContent();
    }
}

