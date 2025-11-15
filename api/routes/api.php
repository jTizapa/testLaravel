<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\PlanController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::prefix('v1')
    ->middleware([
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
        'throttle:api',
    ])->group(function () {
        Route::post('auth/register', [AuthController::class, 'register']);
        Route::post('auth/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('auth/logout', [AuthController::class, 'logout']);
            Route::get('auth/me', [AuthController::class, 'me']);

            // Members CRUD
            Route::apiResource('members', MemberController::class);

            // Plans CRUD
            Route::apiResource('plans', PlanController::class);
        });
    });
