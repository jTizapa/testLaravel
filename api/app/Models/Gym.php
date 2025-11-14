<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant;

class Gym extends Tenant
{
    use HasFactory;

    // Use Stancl's default `tenants` table and `data` JSON column
    protected $casts = [
        'data' => 'array',
    ];
}
