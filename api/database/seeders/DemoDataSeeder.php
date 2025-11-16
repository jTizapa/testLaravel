<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Planes base
        $monthly = Plan::firstOrCreate(
            ['name' => 'Mensual'],
            ['duration_days' => 30, 'price' => 100, 'active' => true]
        );

        $quarterly = Plan::firstOrCreate(
            ['name' => 'Trimestral'],
            ['duration_days' => 90, 'price' => 270, 'active' => true]
        );

        // Miembros demo
        $alice = Member::firstOrCreate(
            ['email' => 'alice@example.com'],
            ['name' => 'Alice Doe', 'phone' => '555-1001', 'status' => 'active', 'joined_at' => now()->subDays(10)]
        );

        $bob = Member::firstOrCreate(
            ['email' => 'bob@example.com'],
            ['name' => 'Bob Smith', 'phone' => '555-2002', 'status' => 'active', 'joined_at' => now()->subDays(20)]
        );

        // Suscripciones + pagos
        $start = Carbon::now()->startOfDay();
        $subAlice = Subscription::firstOrCreate(
            ['member_id' => $alice->id, 'plan_id' => $monthly->id],
            [
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addDays($monthly->duration_days)->toDateString(),
                'status' => 'active',
                'amount' => $monthly->price,
            ]
        );

        Payment::firstOrCreate(
            [
                'member_id' => $alice->id,
                'subscription_id' => $subAlice->id,
                'external_id' => 'seed-pay-1',
            ],
            [
                'amount' => $monthly->price,
                'method' => 'manual',
                'status' => 'paid',
                'paid_at' => now()->subDays(1),
            ]
        );

        Subscription::firstOrCreate(
            ['member_id' => $bob->id, 'plan_id' => $quarterly->id],
            [
                'start_date' => $start->copy()->subDays(15)->toDateString(),
                'end_date' => $start->copy()->addDays(75)->toDateString(),
                'status' => 'active',
                'amount' => $quarterly->price,
            ]
        );
    }
}
