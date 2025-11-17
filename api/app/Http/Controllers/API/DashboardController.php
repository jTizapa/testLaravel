<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();

        return response()->json([
            'total_members' => Member::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'payments_today' => Payment::whereDate('created_at', $today)->count(),
            'revenue_today' => Payment::whereDate('created_at', $today)->sum('amount'),
        ]);
    }
}
