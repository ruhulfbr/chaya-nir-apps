<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            'total_deposits'  => round($totalDeposits, 2),
            'total_expenses'  => round($totalExpenses, 2),
            'balance'         => round($balance, 2),
            'today_deposits'  => round($todayDeposits, 2),
            'today_expenses'  => round($todayExpenses, 2),
            'member_deposits' => $memberDeposits,
        ], 200);
    }
}
