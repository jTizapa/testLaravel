<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

it('permite CRUD de suscripciones con fechas y monto por defecto', function () {
    Sanctum::actingAs(User::factory()->create());

    $member = postJson('/api/v1/members', [
        'name' => 'John',
        'email' => 'john@example.com',
    ])->json();

    $plan = postJson('/api/v1/plans', [
        'name' => 'Mensual',
        'duration_days' => 30,
        'price' => 100,
    ])->json();

    $sub = postJson('/api/v1/subscriptions', [
        'member_id' => $member['id'],
        'plan_id' => $plan['id'],
    ])->assertCreated()->json();

    getJson('/api/v1/subscriptions')->assertOk()->assertJsonPath('data.0.id', $sub['id']);

    getJson("/api/v1/subscriptions/{$sub['id']}")->assertOk()->assertJsonPath('plan.id', $plan['id']);

    putJson("/api/v1/subscriptions/{$sub['id']}", [
        'status' => 'past_due',
    ])->assertOk()->assertJsonPath('status', 'past_due');

    deleteJson("/api/v1/subscriptions/{$sub['id']}")->assertNoContent();
});

it('valida entradas incorrectas en suscripciones', function () {
    Sanctum::actingAs(User::factory()->create());

    postJson('/api/v1/subscriptions', [])->assertStatus(422);

    postJson('/api/v1/subscriptions', [
        'member_id' => 999,
        'plan_id' => 999,
    ])->assertStatus(422);
});
