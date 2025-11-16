<?php

use App\Console\Commands\SendExpiryNotifications;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionExpiring;

uses(RefreshDatabase::class);

it('envía notificaciones para suscripciones que vencen', function () {
    Notification::fake();

    $member = Member::factory()->create(['email' => 'notify@example.com']);
    $plan = Plan::create([
        'name' => 'Mensual',
        'duration_days' => 30,
        'price' => 100,
        'active' => true,
    ]);

    Subscription::create([
        'member_id' => $member->id,
        'plan_id' => $plan->id,
        'start_date' => Carbon::now()->toDateString(),
        'end_date' => Carbon::now()->toDateString(),
        'status' => 'active',
        'amount' => 100,
    ]);

    Subscription::create([
        'member_id' => $member->id,
        'plan_id' => $plan->id,
        'start_date' => Carbon::now()->toDateString(),
        'end_date' => Carbon::now()->addDays(7)->toDateString(),
        'status' => 'active',
        'amount' => 100,
    ]);

    $this->artisan('subscriptions:notify-expiring')
        ->expectsOutputToContain('Notificaciones enviadas')
        ->assertExitCode(0);

    Notification::assertCount(2);
    Notification::assertSentTimes(SubscriptionExpiring::class, 2);
});
