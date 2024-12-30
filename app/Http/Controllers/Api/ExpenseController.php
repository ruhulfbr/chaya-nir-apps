<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $limit = $this->getLimit($request);

        $sort_column = $request->query('sort_column', 'id');
        $sort_order  = $request->query('sort_order', 'desc');

        $title = $request->query('title');
        $start_date = $request->query('start_date');
        $end_date   = $request->query('end_date');
        $amount = $request->query('amount');
        $spent_by = $request->query('spent_by');
        $category = $request->query('category_id');

        $expenses = Expense::query();

        $expenses->when($title, function ($query, $title) {
            $query->where('title', 'LIKE', '%' . $title . '%');
        });
        $expenses->when($start_date, function ($query, $start_date) {
            $query->whereDate('spent_at', '>=', $start_date);
        });
        $expenses->when($end_date, function ($query, $end_date) {
             $query->whereDate('spent_at', '<=', $end_date);
        });
        $expenses->when($amount, function ($query, $amount) {
             $query->where('amount', $amount);
        });
        $expenses->when($spent_by, function ($query, $spent_by) {
            $query->where('spent_by', $spent_by);
        });
        $expenses->when($category, function ($query, $category) {
            $query->where('category_id', $category);
        });

        $expenses = $expenses->orderBy($sort_column, $sort_order)->with(['spentBy', 'category'])->paginate($limit);

        return ExpenseResource::collection($expenses);
    }

    public function show($id)
    {
        $expense = Expense::where('id', $id)->with('spentBy')->firstOrFail();

        return new ExpenseResource($expense);
    }

    public function store(CreateExpenseRequest $request): JsonResponse
    {
        try {
            $data               = $request->validated();
            $data['created_by'] = auth()->id();

            Expense::create($data);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to create expense item',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'expense record created successfully',
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateExpenseRequest $request, $id): JsonResponse
    {
        $expense = Expense::where('id', $id)->firstOrFail();

        try {
            $expense->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to update expense',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'expense updated successfully',
        ]);
    }

    public function destroy($ids): JsonResponse
    {
        $ids = explode(',', $ids);

        try {
            Expense::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'failed to delete expense item',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'expense item deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
