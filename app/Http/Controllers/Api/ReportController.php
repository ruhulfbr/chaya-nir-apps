<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getDashBoardReports(Request $request)
    {
        $totalDeposits = Deposit::sum('amount');
        $totalExpenses = Expense::when($request->stair_no, function ($query) use ($request) {
           $query->where('stair_no', $request->stair_no);
        })->sum('amount');
        $balance       = $totalDeposits - $totalExpenses;

        return response()->json([
            'total_deposits'    => number_format($totalDeposits),
            'total_expenses'    => number_format($totalExpenses),
            'balance'           => number_format($balance),
            'member_deposits'   => $this->getMembersDeposits(),
            'category_expenses' => $this->getCategoryExpenses($request),
        ]);
    }

    private function getMembersDeposits()
    {
        $deposits = Deposit::selectRaw('member_id, SUM(amount) as total_amount')
                                 ->with('member') // Load the related member
                                 ->groupBy('member_id')
                                 ->get();

        $data = $deposits->map(function ($deposit) {
            return [
                'member_id'    => $deposit->member_id,
                'member_name'  => $deposit->member->name ?? 'Unknown',
                'total_amount' => number_format($deposit->total_amount),
            ];
        });

        return $data->toArray();
    }

    private function getCategoryExpenses(Request $request)
    {
        $expenses = Expense::selectRaw('category_id, SUM(amount) as total_amount')
                           ->when($request->stair_no, function ($query) use ($request) {
                               $query->where('stair_no', $request->stair_no);
                           })
                           ->with('category') // Load the related member
                           ->groupBy('category_id')
                           ->get();

        $data = $expenses->map(function ($expense) {
            return [
                'category_id'   => $expense->category_id,
                'category_name' => $expense->category->name ?? 'Unknown',
                'total_amount'  => number_format($expense->total_amount),
            ];
        });

        return $data->toArray();
    }
}
