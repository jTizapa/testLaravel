<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::with(['member','subscription.plan'])->latest()->paginate(15);
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        $subscription = Subscription::with('member')->findOrFail($data['subscription_id']);
        $member = $subscription->member;

        $payment = Payment::create([
            'member_id' => $member->id,
            'subscription_id' => $subscription->id,
            'amount' => $data['amount'],
            'method' => $data['method'] ?? 'manual',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Marcar suscripción como activa si procede
        $subscription->update(['status' => 'active']);

        return response()->json($payment->load(['member','subscription.plan']), 201);
    }
}
