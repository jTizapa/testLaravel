<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('crea pagos manuales y activa la suscripción', function () {
    Sanctum::actingAs(User::factory()->create());

    $member = postJson('/api/v1/members', [
        'name' => 'Jane',
        'email' => 'jane@example.com',
    ])->json();

    $plan = postJson('/api/v1/plans', [
        'name' => 'Mensual',
        'duration_days' => 30,
        'price' => 100,
    ])->json();

    $subscription = postJson('/api/v1/subscriptions', [
        'member_id' => $member['id'],
        'plan_id' => $plan['id'],
    ])->json();

    $payment = postJson('/api/v1/payments', [
        'subscription_id' => $subscription['id'],
        'amount' => 100,
        'method' => 'manual',
    ])->assertCreated()->json();

    expect($payment['status'])->toBe('paid');
    expect($payment['subscription']['status'])->toBe('active');
});
