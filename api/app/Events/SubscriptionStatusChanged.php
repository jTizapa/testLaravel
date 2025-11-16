<?php

namespace App\Events;

use App\Models\Subscription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('dashboard')];
    }

    public function broadcastAs(): string
    {
        return 'subscription.status_changed';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->subscription->id,
            'member' => $this->subscription->member->only(['id','name','email']),
            'plan' => $this->subscription->plan->only(['id','name']),
            'status' => $this->subscription->status,
            'end_date' => $this->subscription->end_date,
        ];
    }
}
