<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'duration_days', 'price', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'subscriptions')
            ->withPivot(['start_date', 'end_date', 'status', 'amount'])
            ->withTimestamps();
    }
}

