<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

it('permite el CRUD de planes', function () {
    Sanctum::actingAs(User::factory()->create());

    $create = postJson('/api/v1/plans', [
        'name' => 'Mensual',
        'duration_days' => 30,
        'price' => 100,
        'active' => true,
    ]);

    $create->assertCreated()->assertJsonPath('name', 'Mensual');
    $planId = $create->json('id');

    getJson('/api/v1/plans')->assertOk()->assertJsonPath('data.0.id', $planId);

    getJson("/api/v1/plans/{$planId}")->assertOk()->assertJsonPath('price', '100.00');

    putJson("/api/v1/plans/{$planId}", [
        'active' => false,
        'price' => 120,
    ])->assertOk()->assertJsonPath('active', false);

    deleteJson("/api/v1/plans/{$planId}")->assertNoContent();
});

it('valida campos al crear planes', function () {
    Sanctum::actingAs(User::factory()->create());

    postJson('/api/v1/plans', [])->assertStatus(422);

    postJson('/api/v1/plans', [
        'name' => 'Invalid',
        'duration_days' => 0,
        'price' => -1,
    ])->assertStatus(422);
});
