<?php

namespace App\Http\Controllers;

use App\Models\MonthlyCompliance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the month and year from the request, default to the current month and year
        $monthYear = $request->input('month_year', date('m-Y'));
        list($month, $year) = explode('-', $monthYear);

        // Fetch compliance data for the specified month and year
        $data = $this->getComplianceData($month, $year);

        return view('components.dashboard', compact('data', 'month', 'year'));
    }

    private function getComplianceData($month, $year)
    {
        // Assuming you have access to the current user
        $user = Auth::user(); // Get the currently authenticated user

        // Get the current date
        $currentDate = now(); // You can also use Carbon::now() if you have Carbon imported

        // Check if the user meets the criteria
        if (in_array($user->department_id, [1, 2]) && in_array($user->role_id, [1, 2, 3])) {
            // User has access to all compliance data
            $completed = MonthlyCompliance::whereMonth('computed_deadline', $month)
                ->whereYear('computed_deadline', $year)
                ->where('status', 'completed')
                ->count();

            $inProgress = MonthlyCompliance::whereMonth('computed_deadline', $month)
                ->whereYear('computed_deadline', $year)
                ->where('status', 'in_progress')
                ->count();

            $pending = MonthlyCompliance::whereMonth('computed_deadline', $month)
                ->whereYear('computed_deadline', $year)
                ->where('status', 'pending')
                ->count();

            // Count overdue items without filtering by month and year
            $overdue = MonthlyCompliance::where('status', '!=', 'completed')
                ->where('computed_deadline', '<', $currentDate)
                ->count();
        } else {
            // User can only see their respective department's compliance data
            $completed = MonthlyCompliance::whereMonth('computed_deadline', $month)
                ->whereYear('computed_deadline', $year)
                ->where('status', 'completed')
                ->where('department_id', $user->department_id)
                ->count();

            $inProgress = MonthlyCompliance::whereMonth('computed_deadline', $month)
                ->whereYear('computed_deadline', $year)
                ->where('status', 'in_progress')
                ->where('department_id', $user->department_id)
                ->count();

            $pending = MonthlyCompliance::whereMonth('computed_deadline', $month)
                ->whereYear('computed_deadline', $year)
                ->where('status', 'pending')
                ->where('department_id', $user->department_id)
                ->count();

            // Count overdue items without filtering by month and year
            $overdue = MonthlyCompliance::where('status', '!=', 'completed')
                ->where('department_id', $user->department_id)
                ->where('computed_deadline', '<', $currentDate)
                ->count();
        }

        $total = $completed + $inProgress + $pending + $overdue;

        return [
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'overdue' => $overdue,
            'total' => $total
        ];
    }
}