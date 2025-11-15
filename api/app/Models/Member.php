<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'status', 'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'subscriptions')
            ->withPivot(['start_date', 'end_date', 'status', 'amount'])
            ->withTimestamps();
    }
}

