<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('procesa pagos via webhook con secreto válido', function () {
    Sanctum::actingAs(User::factory()->create());

    $member = postJson('/api/v1/members', [
        'name' => 'Mark',
        'email' => 'mark@example.com',
    ])->json();

    $plan = postJson('/api/v1/plans', [
        'name' => 'Mensual',
        'duration_days' => 30,
        'price' => 80,
    ])->json();

    $subscription = postJson('/api/v1/subscriptions', [
        'member_id' => $member['id'],
        'plan_id' => $plan['id'],
    ])->json();

    // Usamos el valor por defecto definido en .env.example
    $secret = env('PAYMENTS_WEBHOOK_SECRET', 'localwebhooksecret');

    $resp = postJson('/api/v1/webhooks/payments', [
        'subscription_id' => $subscription['id'],
        'amount' => 80,
        'status' => 'paid',
        'external_id' => 'evt_123',
    ], [
        'X-Webhook-Secret' => $secret,
    ]);

    $resp->assertOk()->assertJson(['ok' => true]);
});

it('rechaza webhook sin secreto', function () {
    postJson('/api/v1/webhooks/payments', [
        'subscription_id' => 1,
        'amount' => 10,
        'status' => 'paid',
    ])->assertStatus(401);
});
