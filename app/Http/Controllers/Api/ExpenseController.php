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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $limit = $this->getLimit($request);

        $sort_column = $request->query('sort_column', 'id');
        if ($sort_column == 'date') {
            $sort_column = 'spent_at';
        }
        $sort_order = $request->query('sort_order', 'desc');

        $title      = $request->query('title');
        $start_date = $request->query('start_date');
        $end_date   = $request->query('end_date');
        $amount     = $request->query('amount');
        $spent_by   = $request->query('spent_by');
        $category   = $request->query('category');

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

    public function dateWiseData()
    {
        $data = Cache::remember('expenses_grouped_by_date', 60 * 60 * 24, function () {
            return Expense::select(DB::raw('DATE(spent_at) as date'), DB::raw('SUM(amount) as total_amount'))
                          ->groupBy('date')
                          ->orderBy('date', 'desc')
                          ->get()
                          ->map(function ($item) {
                              $rows = Expense::select('id', 'title', 'amount')
                                             ->whereDate('spent_at', $item->date)
                                             ->get();
                              return [
                                  'date'         => date('d-m-Y', strtotime($item->date)),
                                  'total_amount' => number_format($item->total_amount),
                                  'rows'         => $rows,
                              ];
                          });
        });

        return response()->json($data);
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
            Cache::forget('expenses_grouped_by_date');
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
            Cache::forget('expenses_grouped_by_date');
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
            Cache::forget('expenses_grouped_by_date');
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
