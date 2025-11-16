<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        return Member::query()->latest()->paginate(15);
    }

    public function store(StoreMemberRequest $request)
    {
        $member = Member::create($request->validated());
        return response()->json($member, 201);
    }

    public function show(Member $member)
    {
        return $member->load('subscriptions.plan');
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->update($request->validated());
        return response()->json($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return response()->noContent();
    }
}
