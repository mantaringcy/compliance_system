<?php

namespace App\Services;

use App\Models\Compliance;
use App\Models\Department;
use App\Models\MonthlyCompliance;
use Carbon\Carbon;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class ComplianceService
{
    // Create Monthly Compliance for a given module
    public function monthlyProjections()
    {
       $result = $this->allCompliances(); 

        // Grouping by month and year
        $groupedResults = [];

        foreach ($result as $item) {
            $deadlineDate = \Carbon\Carbon::parse($item['deadline']);
            $monthYear = $deadlineDate->format('F Y'); // e.g., "October 2024"

            // Initialize the month/year array if not set
            if (!isset($groupedResults[$monthYear])) {
                $groupedResults[$monthYear] = [];
            }

            // Add the compliance item to the corresponding month/year
            $groupedResults[$monthYear][] = $item;
        }

        // return $groupedResults;

        // Current URI
        // $currentRouteName = Route::currentRouteName();

        // Check which view is being requested
        // if ($currentRouteName === 'overview') { // Change this to your actual route
        //     // Get current month deadlines
        //     $currentMonth = Carbon::now()->format('Y-m'); // Format: YYYY-MM
        //     $currentMonthDeadlines = array_filter($result, function($item) use ($currentMonth) {
        //         return Carbon::parse($item['deadline'])->format('Y-m') === $currentMonth;
        //     }); 

        //     foreach ($currentMonthDeadlines as $monthlyCompiance) {
        //         MonthlyCompliance::create([
        //             'compliance_id' => $monthlyCompiance['compliance']['id'],
        //             'compliance_name' => $monthlyCompiance['compliance']['compliance_name'],
        //             'department_id' => $monthlyCompiance['compliance']['department_id'],
        //             'status' => 'pending', // Or whatever the status is
        //             'computed_start_date' => $monthlyCompiance['startWorkingOn'],
        //             'computed_submit_date' => $monthlyCompiance['submitOn'],
        //             'computed_deadline' => $monthlyCompiance['deadline'], // Assuming deadline is the reference_date
        //         ]);
        //     }


        //     return view('components.overview', ['currentMonthDeadlines' => $currentMonthDeadlines]);
        // }

        // Pass the results to the Blade view
        return view('components.projection', compact('groupedResults'));
    }

    public function monthlyCompliances()
    {
        $result = $this->allCompliances(); 

        $startOfCurrentMonth = now()->startOfMonth();
        $endOfCurrentMonth = now()->endOfMonth();
    
        foreach ($result as $compliance) {
            // Parse the relevant dates
            $startDate = Carbon::parse($compliance['startWorkingOn']);
            $submitDate = Carbon::parse($compliance['submitOn']);
            $deadline = Carbon::parse($compliance['deadline']);
    
            // Check if startWorkingOn OR deadline falls within the current month
            if (
                $startDate->between($startOfCurrentMonth, $endOfCurrentMonth) ||
                $deadline->between($startOfCurrentMonth, $endOfCurrentMonth)
            ) {
                // Find the related record in monthly_compliances
                $monthlyCompliance = MonthlyCompliance::where('compliance_id', $compliance['compliance']['id'])
                    ->where('department_id', $compliance['compliance']['department_id'])
                    ->where('computed_start_date', $startDate)
                    ->where('computed_submit_date', $submitDate)
                    ->where('computed_deadline', $deadline)
                    ->first();
    
                if ($monthlyCompliance) {
                    // Update the existing record if details have changed
                    $monthlyCompliance->update([
                        'compliance_name' => $compliance['compliance']['compliance_name'],
                        'department_id' => $compliance['compliance']['department_id'],
                        'computed_start_date' => $startDate,
                        'computed_submit_date' => $submitDate,
                        'computed_deadline' => $deadline,
                    ]);
                } else {
                    // Create a new record if it doesn't exist
                    MonthlyCompliance::create([
                        'compliance_id' => $compliance['compliance']['id'],
                        'compliance_name' => $compliance['compliance']['compliance_name'],
                        'department_id' => $compliance['compliance']['department_id'],
                        'status' => $compliance['status'] ?? 'pending', // Retain status or set default
                        'computed_start_date' => $startDate,
                        'computed_submit_date' => $submitDate,
                        'computed_deadline' => $deadline,
                    ]);
                }
            }
        }
    

        // Filter to include both current month's deadlines and start dates
        // $currentMonthDeadlines = array_filter($result, function ($item) use ($currentMonth) {
        //     return Carbon::parse($item['deadline'])->format('Y-m') === $currentMonth || Carbon::parse($item['startWorkingOn'])->format('Y-m') === $currentMonth;
        // });

        // foreach ($currentMonthDeadlines as $monthlyCompliance) {
        //     // Check if the record exists for the current compliance and department
        //     $existingCompliance = MonthlyCompliance::where('compliance_id', $monthlyCompliance['compliance']['id'])
        //         ->where('department_id', $monthlyCompliance['compliance']['department_id'])
        //         ->where(function ($query) use ($monthlyCompliance, $currentMonth) {
        //             $query->whereBetween('computed_deadline', [now()->startOfMonth(), now()->endOfMonth()])
        //                 ->orWhereBetween('computed_start_date', [now()->startOfMonth(), now()->endOfMonth()]);
        //         })
        //         ->first();

        //     // Prepare the new data for creation or comparison
        //     $newData = [
        //         'compliance_name' => $monthlyCompliance['compliance']['compliance_name'],
        //         'computed_deadline' => $monthlyCompliance['deadline'], // Assuming this is the reference_date
        //         'computed_start_date' => null, // Default to null, will set if current month
        //     ];

        //     // Check if start date is within the current month
        //     if (Carbon::parse($monthlyCompliance['startWorkingOn'])->format('Y-m') === $currentMonth) {
        //         $newData['computed_start_date'] = $monthlyCompliance['startWorkingOn'];
        //     }

        //     // Check if the compliance exists
        //     if ($existingCompliance) {
        //         // If the compliance exists and has differences, update it
        //         if (
        //             $existingCompliance->status !== 'approved' && // Only update if not approved
        //             (
        //                 $existingCompliance->compliance_name !== $newData['compliance_name'] ||
        //                 $existingCompliance->computed_start_date !== $newData['computed_start_date'] ||
        //                 $existingCompliance->computed_deadline !== $newData['computed_deadline']
        //             )
        //         ) {
        //             $existingCompliance->update($newData); // Update only fields in $newData, preserving `status`
        //         }
        //     } else {
        //         // If the compliance does not exist, create a new record with default status
        //         MonthlyCompliance::create(array_merge($newData, [
        //             'compliance_id' => $monthlyCompliance['compliance']['id'],
        //             'department_id' => $monthlyCompliance['compliance']['department_id'],
        //             'status' => 'pending', // Default status only for new records
        //         ]));
        //     }

        //     // Handle the next month's compliance if the CSD is in the current month
        //     $nextMonthStart = Carbon::parse($monthlyCompliance['startWorkingOn'])->addMonth();
        //     $nextMonthDeadline = Carbon::parse($monthlyCompliance['deadline'])->addMonth();

        //     // Check if the next month's computed start date is within the current month
        //     if ($nextMonthStart->format('Y-m') === $currentMonth) {
        //         // Prepare data for the next month's compliance
        //         $nextMonthData = [
        //             'compliance_name' => $monthlyCompliance['compliance']['compliance_name'],
        //             'computed_deadline' => $nextMonthDeadline, // Deadline is in the next month
        //             'computed_start_date' => $nextMonthStart,   // Start date is in the current month
        //         ];

        //         // Check if the next month's compliance already exists in the database
        //         $existingNextMonthCompliance = MonthlyCompliance::where('compliance_id', $monthlyCompliance['compliance']['id'])
        //             ->where('department_id', $monthlyCompliance['compliance']['department_id'])
        //             ->where('computed_start_date', $nextMonthStart)
        //             ->first();

        //         if (!$existingNextMonthCompliance) {
        //             // Create next month's compliance if it doesn't exist
        //             MonthlyCompliance::create(array_merge($nextMonthData, [
        //                 'compliance_id' => $monthlyCompliance['compliance']['id'],
        //                 'department_id' => $monthlyCompliance['compliance']['department_id'],
        //                 'status' => 'pending', // Default status for the new record
        //             ]));
        //         }
        //     }
        // }
        

        return $result;

        // return view('components.overview', ['currentMonthDeadlines' => $currentMonthDeadlines]);

        
    }

    public function complianceOverview($user)
    {
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
    
        // Check if the user has access to all compliances
        $hasFullAccess = in_array($user->role_id, [1, 2, 3]) && in_array($user->department_id, [1, 2]);
    
        // Filter compliances based on user access
        $query = MonthlyCompliance::where(function ($query) use ($currentMonthStart, $currentMonthEnd) {
            $query->whereBetween('computed_start_date', [$currentMonthStart, $currentMonthEnd])
                  ->orWhereBetween('computed_deadline', [$currentMonthStart, $currentMonthEnd]);
        });
    
        if (!$hasFullAccess) {
            // Limit to the user's department
            $query->where('department_id', $user->department_id);
        }
    
        // Get total and completed compliances
        $totalCompliances = $query->count();
        $completedCompliances = $query->where('status', 'completed')->count();
    
        // Calculate percentage
        $completionPercentage = $totalCompliances > 0
            ? round(($completedCompliances / $totalCompliances) * 100, 2)
            : 0;
    
        return [
            'totalCompliances' => $totalCompliances,
            'completedCompliances' => $completedCompliances,
            'completionPercentage' => $completionPercentage,
            'hasFullAccess' => $hasFullAccess, // Pass this for Blade display logic
        ];
    }

    private function allCompliances()
    {
        $departments = Department::all();

        $userDepartmentId = Auth::user()->department_id;

        if ($userDepartmentId == 1) {
            // Admin can view all compliances
            $compliancesByDepartment = Compliance::all();
        } else {
            // Regular users only see compliances for their department
            $compliancesByDepartment = Compliance::where('department_id', $userDepartmentId)->get();
        }

        // Process compliance deadlines
        $complianceDeadlines = [];

        foreach ($compliancesByDepartment as $compliance) {
            $referenceDate = Carbon::parse($compliance->reference_date);
            $frequency = $compliance->frequency;

            // Calculate the next deadline based on the frequency
            $deadlines = $this->calculateDeadlines($referenceDate, $frequency);


            // Add valid deadlines to the result
            foreach ($deadlines as $deadline) {

                $complianceDeadlines[] = [
                    'compliance' => $compliance,
                    'reference_date' => $referenceDate->toDateString(),
                    'deadline' => $deadline->toDateString(),
                ];
            }
        }


        $result = [];

        usort($complianceDeadlines, function($a, $b) {
            return $a['deadline'] <=> $b['deadline']; // Compare the deadlines
        });

        // Iterate through the data to extract compliance_name and deadlines
        foreach ($complianceDeadlines as $item) {
            $daysLeft = $this->calculateDaysLeft($item['deadline']);
            $startWorkingOn = $this->computeStartWorkingOn($item['deadline'], $item['compliance']['start_working_on']);
            $submitOn = $this->computeSubmitOn($item['deadline'], $item['compliance']['submit_on']);


            $result[] = [
                'compliance' => $item['compliance'],
                'compliance_department' =>  $this->getDepartmentName($item['compliance']['department_id'], $departments),
                'startWorkingOn' => $startWorkingOn,
                'submitOn' => $submitOn,
                'deadline' => $item['deadline'],
                'days_left' => $daysLeft, // Add days left to the result

            ];
        }

        return $result;
    }

    // Get 12-month projections for the module
    public function getProjections($departmentId)
    {

    }

     // Calculate the Date Today and the Deadline
     private function calculateDaysLeft($deadline) {
        // Get the current date
        $currentDate = Carbon::now()->startOfDay(); // Use startOfDay to ignore the time part
    
        // Create a Carbon instance from the deadline date
        $deadlineDate = Carbon::parse($deadline)->startOfDay(); // Ensure we consider only the date part
    
        // Calculate the difference in days (negative if past)
        $daysLeft = $deadlineDate->diffInDays($currentDate, false); // 'false' returns a negative value if the deadline is in the past
    
        return $daysLeft; // Return the difference as is, no need to round since it's an integer
    }

    // Compute the Deadline base on start_working_on in database
    private function computeStartWorkingOn($deadline, $startWorkingOn) {
        // Create a Carbon instance from the deadline date
        $deadlineDate = Carbon::parse($deadline);
    
        // Adjust the deadline based on the start_working_on value
        switch ($startWorkingOn) {
            case 1: // 3 Days Before
                $adjustedDate = $deadlineDate->subDays(3);
                break;
            case 2: // 1 Week Before
                $adjustedDate = $deadlineDate->subWeeks(1);
                break;
            case 3: // 2 Weeks Before
                $adjustedDate = $deadlineDate->subWeeks(2);
                break;
            case 4: // 3 Weeks Before
                $adjustedDate = $deadlineDate->subWeeks(3);
                break;
            case 5: // 1 Month Before
                $adjustedDate = $deadlineDate->subMonths(1);
                break;
            case 6: // 1.5 Months Before
                $adjustedDate = $deadlineDate->subMonths(1)->subDays(15);
                break;
            case 7: // 2 Months Before
                $adjustedDate = $deadlineDate->subMonths(2);
                break;
            case 8: // 3 Months Before
                $adjustedDate = $deadlineDate->subMonths(3);
                break;
            case 9: // 4 Months Before
                $adjustedDate = $deadlineDate->subMonths(4);
                break;
            case 10: // 5 Months Before
                $adjustedDate = $deadlineDate->subMonths(5);
                break;
            default: // If no valid start_working_on value is found
                $adjustedDate = $deadlineDate; // No adjustment
                break;
        }
    
        return $adjustedDate->format('Y-m-d'); // Format to desired output
    }
    
    // Compute the Deadline base on submit_on in database
    private function computeSubmitOn($deadline, $submitOn) {
        // Create a Carbon instance from the deadline date
        $deadlineDate = Carbon::parse($deadline);
    
        // Adjust the deadline based on the start_working_on value
        switch ($submitOn) {
            case 1: // 3 Days Before
                $adjustedDate = $deadlineDate->subDays(3);
                break;
            case 2: // 1 Week Before
                $adjustedDate = $deadlineDate->subWeeks(1);
                break;
            case 3: // 2 Weeks Before
                $adjustedDate = $deadlineDate->subWeeks(2);
                break;
            case 4: // 1 Month Before
                $adjustedDate = $deadlineDate->subMonths(1);
                break;
            case 5: // 2 Months Before
                $adjustedDate = $deadlineDate->subMonths(2);
                break;
            case 6: // 3 Months Before
                $adjustedDate = $deadlineDate->subMonths(3);
                break;
            case 7: // 4 Months Before
                $adjustedDate = $deadlineDate->subMonths(4);
                break;
            default: // If no valid start_working_on value is found
                $adjustedDate = $deadlineDate; // No adjustment
                break;
        }
    
        return $adjustedDate->format('Y-m-d'); // Format to desired output
    }

    // Compute the deadline frequency base on reference_date in database
    private function calculateDeadlines($referenceDate, $frequency)
    {
        $currentDateStart = Carbon::now();
        $endMonth = $currentDateStart->copy()->addDays(365);

        $deadlines = [];
        $sortedDeadlines = [];

        switch ($frequency) {
            case 1: // Monthly
                // Start calculating from the next month of the reference date
                $startMonth = $referenceDate->copy()->addDays(30.42);

                while ($startMonth->lessThanOrEqualTo($endMonth)) {
                    $deadlines[] = $startMonth->copy(); // Add the deadline
                    $startMonth->addDays(30.42); // Move to the next month
                }
                break;
            case 2: // Quarterly
                $startMonth = $referenceDate->copy()->addDays(91.25);

                while ($startMonth->lessThanOrEqualTo($endMonth)) {
                    $deadlines[] = $startMonth->copy(); // Add the deadline
                    $startMonth->addDays(91.25); // Move to the next month
                }
                break;
            case 3: // Semi-Annually
                $startMonth = $referenceDate->copy()->addDays(182.5);

                while ($startMonth->lessThanOrEqualTo($endMonth)) {
                    $deadlines[] = $startMonth->copy(); // Add the deadline
                    $startMonth->addDays(182.5); // Move to the next month
                }
                break;
            case 4: // Annually
                $startMonth = $referenceDate->copy()->addDays(365);

                while ($startMonth->lessThanOrEqualTo($endMonth)) {
                    $deadlines[] = $startMonth->copy(); // Add the deadline
                    $startMonth->addDays(365); // Move to the next month
                }
                break;
            case 5: // Every 3 Years
                $startMonth = $referenceDate->copy()->addDays(1095);

                while ($startMonth->lessThanOrEqualTo($endMonth)) {
                    $deadlines[] = $startMonth->copy(); // Add the deadline
                    $startMonth->addDays(1095); // Move to the next month
                }
                break;
            case 6: // Every 5 Years
                $startMonth = $referenceDate->copy()->addDays(1825);

                while ($startMonth->lessThanOrEqualTo($endMonth)) {
                    $deadlines[] = $startMonth->copy(); // Add the deadline
                    $startMonth->addDays(1825); // Move to the next month
                }
                break;
        }

        // Filtered Date
        $currentMonthStart = Carbon::now()->startOfMonth(); // Get the start of the current month

        $filteredDeadlines = array_filter($deadlines, function ($deadline) use ($currentMonthStart) {
            return $deadline->greaterThanOrEqualTo($currentMonthStart);
        });

        // usort($filteredDeadlines, function ($a, $b) {
        //     return strtotime($a) - strtotime($b); // Sort using Unix timestamps
        // });

        return $filteredDeadlines;
    }

    public function getDepartmentName($departmentId, $departments) {
        return $departments[$departmentId - 1]['department_name'] ?? 'Unknown Department';
    }
}