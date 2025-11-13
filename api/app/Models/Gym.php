<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant;

class Gym extends Tenant
{
    use HasFactory;

    protected $table = 'gyms';

    protected $fillable = [
        'name',
        'domain',
        'database',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
