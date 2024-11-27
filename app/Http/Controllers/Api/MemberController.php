<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\CreateMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $limit       = $request->query('limit') && $request->query('limit') < 100 ? $request->query('limit') : 10;
        $name        = $request->query('name');
        $phoneNumber = $request->query('phone_number');

        $member = Member::query();
        $member->when($name, function ($query, $name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        });
        $member->when($phoneNumber, function ($query, $phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        });
        $member = $member->where('category_type', 'income')->paginate($limit);

        return MemberResource::collection($member);
    }

    public function show($id)
    {
        $member = Member::where('id', $id)->firstOrFail();

        return new MemberResource($member);
    }

    public function store(CreateMemberRequest $request)
    {
        try {
            Member::create($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to create member',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Member created successfully',
        ], 201);
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $member = Member::where('id', $id)->firstOrFail();

        try {
            $member->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to update member',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'member updated successfully',
        ]);
    }

    public function delete($ids)
    {
        $ids = explode(',', $ids);

        try {
            Member::whereIn('id', $ids)->delete();
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to delete member',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'member deleted successfully',
        ], 204);
    }
}
