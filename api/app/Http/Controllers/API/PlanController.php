<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::query()->latest()->paginate(15);
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->validated());
        return response()->json($plan, 201);
    }

    public function show(Plan $plan)
    {
        return $plan;
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());
        return response()->json($plan);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->noContent();
    }
}
