<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\CreateMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $limit       = $request->query('limit') && $request->query('limit') < 100 ? $request->query('limit') : 10;
        $name        = $request->query('name');
        $phoneNumber = $request->query('phone');

        $member = Member::query();
        $member->when($name, function ($query, $name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        });
        $member->when($phoneNumber, function ($query, $phoneNumber) {
            $query->where('phone', 'LIKE', '%' . $phoneNumber . '%');
        });
        $member = $member->withSum('deposits', 'amount')->paginate($limit);

        return MemberResource::collection($member);
    }

    public function show($id)
    {
        $member = Member::where('id', $id)->withSum('deposits', 'amount')->firstOrFail();

        return new MemberResource($member);
    }

    public function store(CreateMemberRequest $request)
    {
        try {
            Member::create($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create member',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Member created successfully',
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $member = Member::where('id', $id)->firstOrFail();

        try {
            $member->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update member',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Member updated successfully',
        ]);
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);

        try {
            Member::whereIn('id', $ids)->delete();
        } catch (Exception $e) {

            if ($e->getCode() === "23000") { // 23000 is the SQLSTATE code for integrity constraint violations
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Cannot delete this member because there are associated data.',
                    'error'   => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete member',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Member deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
