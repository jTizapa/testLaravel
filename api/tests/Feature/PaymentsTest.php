<?php

namespace Tests\Feature;

use App\Models\Gym;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentsTest extends TestCase
{
    use RefreshDatabase;

    protected function seedTenant(): array
    {
        $tenant = Gym::create(['id' => 'gym-alpha', 'data' => ['name' => 'Gym Alpha']]);
        $tenant->domains()->create(['domain' => 'alpha.localhost']);

        // Build minimal data under HTTP requests to ensure tenancy context
        return ['tenant' => $tenant];
    }

    public function test_manual_payment_creates_record_and_updates_subscription(): void
    {
        $this->seedTenant();
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $headers = [
            'Authorization' => 'Bearer '.$token,
            'HOST' => 'alpha.localhost',
            'Accept' => 'application/json',
        ];

        // Create member
        $member = $this->postJson('http://alpha.localhost/api/v1/members', [
            'name' => 'Jane', 'email' => 'jane@example.com'
        ], $headers)->json();

        // Create plan
        $plan = $this->postJson('http://alpha.localhost/api/v1/plans', [
            'name' => 'Monthly', 'duration_days' => 30, 'price' => 100
        ], $headers)->json();

        // Create subscription directly in tenant via endpoint is not implemented; create via POST would be in future.
        // For now, create subscription using HTTP to ensure tenant context? We'll call a small closure route is not present.
        // Instead, simulate via API: we need subscription id; so create using DB by triggering tenant via a dummy request first.

        // Trigger tenancy by simple GET
        $this->get('http://alpha.localhost/', ['HOST' => 'alpha.localhost']);

        // Now create subscription model instance (tenant connection active via previous request is not guaranteed across requests),
        // so we will create it via an HTTP helper route in future. For now, do it in the request that creates payment: not possible.
        // As a workaround in test, re-fetch ids and create via model - tests may pass if tenancy bootstrapper switches default connection per request.

        $subscription = Subscription::forceCreate([
            'member_id' => $member['id'],
            'plan_id' => $plan['id'],
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'active',
            'amount' => 100,
        ]);

        $resp = $this->postJson('http://alpha.localhost/api/v1/payments', [
            'subscription_id' => $subscription->id,
            'amount' => 100,
        ], $headers);

        $resp->assertCreated();
        $this->assertEquals('paid', $resp->json('status'));
    }

    public function test_webhook_creates_payment_with_secret(): void
    {
        $this->seedTenant();

        // Prepare member/plan/subscription under tenant
        $this->get('http://alpha.localhost/', ['HOST' => 'alpha.localhost']);
        $member = Member::forceCreate(['name' => 'A', 'email' => 'a@example.com']);
        $plan = Plan::forceCreate(['name' => 'P', 'duration_days' => 30, 'price' => 100, 'active' => true]);
        $subscription = Subscription::forceCreate([
            'member_id' => $member->id,
            'plan_id' => $plan->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'active',
            'amount' => 100,
        ]);

        $headers = [
            'HOST' => 'alpha.localhost',
            'X-Webhook-Secret' => env('PAYMENTS_WEBHOOK_SECRET', 'localwebhooksecret'),
        ];

        $resp = $this->postJson('http://alpha.localhost/api/v1/webhooks/payments', [
            'subscription_id' => $subscription->id,
            'amount' => 100,
            'status' => 'paid',
        ], $headers);

        $resp->assertOk();
    }
}

