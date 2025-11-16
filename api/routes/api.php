<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\PlanController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\WebhookController;
use App\Http\Controllers\API\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(['throttle:api'])
    ->group(function () {
        Route::post('auth/register', [AuthController::class, 'register']);
        Route::post('auth/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('auth/logout', [AuthController::class, 'logout']);
            Route::get('auth/me', [AuthController::class, 'me']);

            Route::apiResource('members', MemberController::class);
            Route::apiResource('plans', PlanController::class);
            Route::apiResource('subscriptions', SubscriptionController::class);
            Route::get('payments', [PaymentController::class, 'index']);
            Route::post('payments', [PaymentController::class, 'store']);
        });

        Route::post('webhooks/payments', [WebhookController::class, 'payments']);
    });
