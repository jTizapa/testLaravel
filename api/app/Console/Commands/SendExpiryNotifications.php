<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionExpiring;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendExpiryNotifications extends Command
{
    protected $signature = 'subscriptions:notify-expiring';

    protected $description = 'Envía notificaciones a suscripciones que vencen pronto (hoy y en 7 días)';

    public function handle(): int
    {
        $today = Carbon::now()->toDateString();
        $in7 = Carbon::now()->addDays(7)->toDateString();

        $todaySubs = Subscription::with('member', 'plan')
            ->whereDate('end_date', $today)
            ->get();

        $soonSubs = Subscription::with('member', 'plan')
            ->whereDate('end_date', $in7)
            ->get();

        foreach ($todaySubs as $sub) {
            Notification::route('mail', $sub->member->email)
                ->notify(new SubscriptionExpiring($sub, 'today'));
        }

        foreach ($soonSubs as $sub) {
            Notification::route('mail', $sub->member->email)
                ->notify(new SubscriptionExpiring($sub, '7days'));
        }

        $this->info("Notificaciones enviadas: hoy {$todaySubs->count()}, en 7 días {$soonSubs->count()}");

        return Command::SUCCESS;
    }
}
