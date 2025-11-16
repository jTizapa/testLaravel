<?php

use App\Events\PaymentRecorded;
use App\Events\SubscriptionStatusChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('dispara eventos de broadcast en pagos y suscripciones', function () {
    Event::fake();
    Sanctum::actingAs(User::factory()->create());

    $member = postJson('/api/v1/members', [
        'name' => 'Eva',
        'email' => 'eva@example.com',
    ])->json();

    $plan = postJson('/api/v1/plans', [
        'name' => 'Mensual',
        'duration_days' => 30,
        'price' => 50,
    ])->json();

    $subscription = postJson('/api/v1/subscriptions', [
        'member_id' => $member['id'],
        'plan_id' => $plan['id'],
    ])->json();

    Event::assertDispatched(SubscriptionStatusChanged::class);

    postJson('/api/v1/payments', [
        'subscription_id' => $subscription['id'],
        'amount' => 50,
    ])->assertCreated();

    Event::assertDispatched(PaymentRecorded::class);
});
