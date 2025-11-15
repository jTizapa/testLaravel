<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::query()->latest()->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:plans,name'],
            'duration_days' => ['required','integer','min:1'],
            'price' => ['required','numeric','min:0'],
            'active' => ['boolean'],
        ]);

        $plan = Plan::create($data);
        return response()->json($plan, 201);
    }

    public function show(Plan $plan)
    {
        return $plan;
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:255','unique:plans,name,'.$plan->id],
            'duration_days' => ['sometimes','integer','min:1'],
            'price' => ['sometimes','numeric','min:0'],
            'active' => ['sometimes','boolean'],
        ]);

        $plan->update($data);
        return response()->json($plan);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->noContent();
    }
}

