<?php

namespace Tests\Feature;

use App\Models\Gym;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscriptions_crud(): void
    {
        $tenant = Gym::create(['id' => 'gym-alpha', 'data' => ['name' => 'Gym Alpha']]);
        $tenant->domains()->create(['domain' => 'alpha.localhost']);

        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $headers = [
            'Authorization' => 'Bearer '.$token,
            'HOST' => 'alpha.localhost',
            'Accept' => 'application/json',
        ];

        // Create member
        $member = $this->postJson('http://alpha.localhost/api/v1/members', [
            'name' => 'John', 'email' => 'john@example.com'
        ], $headers)->json();

        // Create plan
        $plan = $this->postJson('http://alpha.localhost/api/v1/plans', [
            'name' => 'Monthly', 'duration_days' => 30, 'price' => 100
        ], $headers)->json();

        // Create subscription
        $sub = $this->postJson('http://alpha.localhost/api/v1/subscriptions', [
            'member_id' => $member['id'],
            'plan_id' => $plan['id'],
        ], $headers)->assertCreated()->json();

        // Show
        $this->getJson('http://alpha.localhost/api/v1/subscriptions/'.$sub['id'], $headers)
            ->assertOk();

        // Update
        $this->putJson('http://alpha.localhost/api/v1/subscriptions/'.$sub['id'], [
            'status' => 'past_due',
        ], $headers)->assertOk()->assertJsonPath('status', 'past_due');

        // Index
        $this->getJson('http://alpha.localhost/api/v1/subscriptions', $headers)->assertOk();

        // Delete
        $this->deleteJson('http://alpha.localhost/api/v1/subscriptions/'.$sub['id'], [], $headers)
            ->assertNoContent();
    }
}

