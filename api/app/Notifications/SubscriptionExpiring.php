<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiring extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Subscription $subscription,
        protected string $when // '7days' | 'today'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Suscripción por vencer ({$this->when})")
            ->line("Tu suscripción al plan {$this->subscription->plan->name} está por vencer.")
            ->line("Fecha de fin: {$this->subscription->end_date}")
            ->line($this->when === 'today' ? 'Vence hoy.' : 'Quedan 7 días.')
            ->action('Revisar cuenta', url('/'))
            ->line('Gracias por usar el gestor de membresías.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'plan' => $this->subscription->plan->name,
            'end_date' => $this->subscription->end_date,
            'when' => $this->when,
        ];
    }
}
