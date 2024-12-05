<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\CreateDepositRequest;
use App\Http\Requests\Deposit\UpdateDepositRequest;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use Exception;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $limit = $this->getLimit($request);

        $sort_column = $request->query('sort_column', 'id');
        $sort_order  = $request->query('sort_order', 'desc');

        $memberId   = $request->query('member_id');
        $receivedBy = $request->query('received_by');
        $start_date = $request->query('start_date');
        $end_date   = $request->query('end_date');
        $amount     = $request->query('amount');

        $deposits = Deposit::query();
        $deposits->when($start_date, function ($query, $start_date) {
            $query->whereDate('deposit_at', '>=', $start_date);
        })
                 ->when($end_date, function ($query, $end_date) {
                     $query->whereDate('deposit_at', '<=', $end_date);
                 })
                 ->when($amount, function ($query, $amount) {
                     $query->where('amount', $amount);
                 })
                 ->when($memberId, function ($query, $memberId) {
                     $query->where('member_id', $memberId);
                 })
                 ->when($receivedBy, function ($query, $receivedBy) {
                     $query->where('received_by', $receivedBy);
                 });
        $deposits = $deposits->orderBy($sort_column, $sort_order)->with(['member', 'receivedBy'])->paginate($limit);

        return DepositResource::collection($deposits);
    }

    public function show($id)
    {
        $deposit = Deposit::where('id', $id)->with(['member', 'receivedBy'])->firstOrFail();

        return new DepositResource($deposit);
    }

    public function store(CreateDepositRequest $request)
    {
        try {
            $data               = $request->validated();
            $data['created_by'] = auth()->id();

            Deposit::create($data);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to create deposit item',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'deposit record created successfully',
        ], 201);
    }

    public function update(UpdateDepositRequest $request, $id)
    {
        $deposit = Deposit::where('id', $id)->firstOrFail();

        try {
            $deposit->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to update deposit',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'deposit updated successfully',
        ], 200);
    }

    public function destroy(string $ids)
    {
        $ids = explode(',', $ids);

        try {
            Deposit::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to delete deposit item',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'deposit item deleted successfully',
        ], 204);
    }
}
