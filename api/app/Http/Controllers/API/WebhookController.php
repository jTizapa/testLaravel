<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function payments(Request $request)
    {
        $secret = env('PAYMENTS_WEBHOOK_SECRET');
        $provided = $request->header('X-Webhook-Secret');
        if (! $secret || $provided !== $secret) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'subscription_id' => ['required','integer'],
            'amount' => ['required','numeric','min:0'],
            'status' => ['required','in:paid,failed'],
            'external_id' => ['nullable','string','max:255'],
        ]);

        $subscription = Subscription::with('member')->findOrFail($data['subscription_id']);

        $payment = Payment::create([
            'member_id' => $subscription->member_id,
            'subscription_id' => $subscription->id,
            'amount' => $data['amount'],
            'method' => 'webhook',
            'status' => $data['status'],
            'external_id' => $data['external_id'] ?? null,
            'paid_at' => $data['status'] === 'paid' ? now() : null,
        ]);

        $subscription->update([
            'status' => $data['status'] === 'paid' ? 'active' : 'past_due',
        ]);

        return response()->json(['ok' => true, 'payment_id' => $payment->id]);
    }
}

