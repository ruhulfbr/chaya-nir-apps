<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function getDashBoardReports()
    {
        $totalDeposits = Deposit::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $balance       = $totalDeposits - $totalExpenses;

        $todayDeposits = Deposit::whereDate('deposit_at', '=', Carbon::now())->sum('amount');
        $todayExpenses = Expense::whereDate('spent_at', '=', Carbon::now())->sum('amount');

        $memberDeposits = Deposit::selectRaw('member_id, SUM(amount) as total_amount')
                                 ->with('member') // Load the related member
                                 ->groupBy('member_id')
                                 ->get();

        return response()->json([
            'total_deposits'  => number_format($totalDeposits),
            'total_expenses'  => number_format($totalExpenses),
            'balance'         => number_format($balance),
            'today_deposits'  => number_format($todayDeposits),
            'today_expenses'  => number_format($todayExpenses),
            'member_deposits' => $memberDeposits,
        ], 200);
    }
}
