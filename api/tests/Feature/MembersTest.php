<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

it('permite realizar el CRUD de miembros', function () {
    Sanctum::actingAs(User::factory()->create());

    $create = postJson('/api/v1/members', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '555-0101',
    ]);

    $create->assertCreated()->assertJsonPath('email', 'john@example.com');
    $memberId = $create->json('id');

    getJson('/api/v1/members')
        ->assertOk()
        ->assertJsonPath('data.0.id', $memberId);

    getJson("/api/v1/members/{$memberId}")
        ->assertOk()
        ->assertJsonPath('email', 'john@example.com');

    putJson("/api/v1/members/{$memberId}", [
        'phone' => '555-9999',
    ])->assertOk()->assertJsonPath('phone', '555-9999');

    deleteJson("/api/v1/members/{$memberId}")
        ->assertNoContent();
});

it('valida campos obligatorios al crear miembros', function () {
    Sanctum::actingAs(User::factory()->create());

    postJson('/api/v1/members', [])->assertStatus(422);

    postJson('/api/v1/members', [
        'name' => 'Jane',
        'email' => 'not-an-email',
    ])->assertStatus(422);
});
