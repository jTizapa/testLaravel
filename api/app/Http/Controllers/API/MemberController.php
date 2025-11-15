<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MemberController extends Controller
{
    public function index()
    {
        return Member::query()->latest()->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:members,email'],
            'phone' => ['nullable','string','max:50'],
            'status' => ['nullable','in:active,inactive'],
            'joined_at' => ['nullable','date'],
        ]);

        $member = Member::create($data);
        return response()->json($member, 201);
    }

    public function show(Member $member)
    {
        return $member->load('subscriptions.plan');
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'email' => ['sometimes','email','max:255','unique:members,email,'.$member->id],
            'phone' => ['nullable','string','max:50'],
            'status' => ['nullable','in:active,inactive'],
            'joined_at' => ['nullable','date'],
        ]);

        $member->update($data);
        return response()->json($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return response()->noContent();
    }
}

