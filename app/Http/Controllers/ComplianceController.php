<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\ComplianceRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Hamcrest\Core\HasToString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ComplianceController extends Controller
{
    // Returns data to the Compliance List Module
    public function index(Request $request)
    {
        $complianceEntries = DB::table('compliances')->get(); // Fetch all entries

        // Show All Compliances by Department
        $userDepartmentId = Auth::user()->department_id;

        if ($userDepartmentId == 1) {
            // Admin can view all compliances
            $compliancesByDepartment = Compliance::all();
        } else {
            // Regular users only see compliances for their department
            $compliancesByDepartment = Compliance::where('department_id', $userDepartmentId)->get();
        }


        if ($request->ajax()) {
            $compliances = Compliance::select('id', 'compliance_name', 'frequency', 'created_at');

            return DataTables::of($compliancesByDepartment)
                ->addColumn('action', function($row){
                    $viewAnchor = '<a href="#" class="view-btn view-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewComplianceModal"
                            data-compliance-id="'.$row->id.'"
                            data-compliance-name="'.$row->compliance_name.'"
                            data-department-id="'.$row->department_id.'"
                            data-compliance-reference-date="'.$row->reference_date.'"
                            data-compliance-frequency="'.$row->frequency.'"
                            data-compliance-start-working-on="'.$row->start_working_on.'"
                            data-compliance-submit-on="'.$row->submit_on.'"
                            ><i class="fa-regular fa-eye"></i></a>';

                    $editAnchor = '<a href="#" class="edit-btn edit-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editComplianceModal"
                            data-compliance-id="'.$row->id.'"
                            data-compliance-name="'.$row->compliance_name.'"
                            data-department-id="'.$row->department_id.'"
                            data-compliance-reference-date="'.$row->reference_date.'"
                            data-compliance-frequency="'.$row->frequency.'"
                            data-compliance-start-working-on="'.$row->start_working_on.'"
                            data-compliance-submit-on="'.$row->submit_on.'"
                            ><i class="fa-regular fa-pen-to-square"></i></a>';

                    $deleteAnchor = '<a href="#" class="delete-btn delete-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteComplianceModal"
                            data-compliance-id="'.$row->id.'"
                            data-compliance-name="'.$row->compliance_name.'"
                            ><i class="fa-regular fa-trash-can"></i></a>';

                    return $viewAnchor. '' .$editAnchor. '' .$deleteAnchor;
                })
                ->make(true);
        }


        $department = DB::table('departments')->pluck('department_name', 'id');

        // dd($department);

        foreach ($compliancesByDepartment as $entry) {

            // $statusKey = (string) $entry->department_id;
            $entry->mapped_department = $department->get($entry->department_id, 'Unknown');


            // Map frequency value
            $statusKey = (string) $entry->frequency;
            $entry->mapped_frequency = Config::get('static_data.frequency.' . $statusKey, 'Unknown');
            
            // Map start_working_on value
            $statusKey = (string) $entry->start_working_on;
            $entry->mapped_startWorkingOn = Config::get('static_data.start_working_on.' . $statusKey, 'Unknown');

            // Map submit_on value
            $statusKey = (string) $entry->submit_on;
            $entry->mapped_submitOn = Config::get('static_data.submit_on.' . $statusKey, 'Unknown');

        }
 
        return view('components.compliance-list', compact('compliancesByDepartment'));
    }

    // Create Compliance
    public function store(Request $request)
    {
        $fields = $request->validate([
            'compliance_name' => ['required'],
            'department_id' => ['required'],
            'reference_date' => ['required'],
            'frequency' => ['required', 'not_in:0,'],
            'start_working_on' => ['required', 'not_in:0,'],
            'submit_on' => ['required', 'not_in:0,']
        ],
        [
            'compliance_name.required' => 'Please provide the name of the compliance.',
            'department_id.required' => 'Please select a department.',
            'reference_date.required' => 'Please provide a reference date.',
            'frequency.required' => 'Please select the frequency of the compliance.',
            'start_working_on.required' => 'Please provide a start date for compliance.',
            'submit_on.required' => 'Please provide a submission date.',
        ]);

        // Check if user is a super admin
        if (Auth::user()->role_id == 3) {
            // Directly create compliance if super admin
            $compliance = Compliance::create($fields);
        } else {
            // dd($request);

            // Store the request for super admin review
            ComplianceRequest::create([
                'user_id' => Auth::id(),
                'action' => 'add',
                'changes' => json_encode($request->all()),
            ]);
        }

        return back()->with('success', 'Your post was created.');
    }

    // Update Compliance
    public function update(Request $request, Compliance $compliance)
    {
        $fields = $request->validate([
            'compliance_name' => ['required'],
            'department_id' => ['required'],
            'reference_date' => ['required'],
            'frequency' => ['required'],
            'start_working_on' => ['required'],
            'submit_on' => ['required']
        ],
        [
            'compliance_name.required' => 'Please provide the name of the compliance.',
            'department_id.required' => 'Please select a department.',
            'reference_date.required' => 'Please provide a reference date.',
            'frequency.required' => 'Please select the frequency of the compliance.',
            'start_working_on.required' => 'Please provide a start date for compliance.',
            'submit_on.required' => 'Please provide a submission date.',
        ]);


        if (Auth::user()->role_id == 3) {
            // Directly update compliance if super admin
            $compliance->update([
                'compliance_name' => $fields['compliance_name'],
                'department_id' => $fields['department_id'],
                'reference_date' => $fields['reference_date'],
                'frequency' => $fields['frequency'],
                'start_working_on' => $fields['start_working_on'],
                'submit_on' => $fields['submit_on']
    
            ]);
        } else {
            // Store the edit request for super admin review
            ComplianceRequest::create([
                'compliance_id' => $compliance->id,
                'user_id' => Auth::id(),
                'action' => 'edit',
                'changes' => json_encode($request->all()),
            ]);
        }

       
        // return response()->json(['success' => 'Compliance updated successfully.']);


        return back()->with('success', 'Your compliance was updated.');
    }

    // Delete Compliance
    public function destroy($id, Request $request)
    {
        // Step 1: Find the Existing Record
        $compliance = Compliance::findOrFail($id); // This will throw a 404 if not found

        if (Auth::user()->role_id == 3) {
            // Directly delete compliance if super admin
            // Step 3: Delete the Record
            $compliance->delete(); // Delete the record
        } else {
            // Store the delete request for super admin review
            ComplianceRequest::create([
                'compliance_id' => $id,
                'user_id' => Auth::id(),
                'action' => 'delete',
                'changes' => json_encode($request->all()), // No changes needed for delete
            ]);
        }        

        // Step 2: Authorize the Action
        // Gate::authorize('delete', $compliance); // Check if the user is authorized

   

        // Step 4: Redirect or Return a Response
        return back()->with('success', 'Compliance deleted successfully.');
        // return response()->json(['message' => 'Compliance deleted successfully!'], 200);
    }

    // REQUEST FOR CHANGE
    public function reviewRequests()
    {
        // Fetch departments
        $departments = Department::all()->pluck('department_name', 'id')->toArray();

        // Get the logged-in user's department_id (assuming it's stored in the authenticated user)
        $userDepartmentId = Auth::user()->department_id;

        // Fetch only the compliance requests for the user's department, excluding approved ones
        $requests = ComplianceRequest::where('approved', false)
        ->when(!in_array($userDepartmentId, [1]), function ($query) use ($userDepartmentId) {
            return $query->whereHas('compliance', function ($complianceQuery) use ($userDepartmentId) {
                $complianceQuery->where('department_id', $userDepartmentId);
            });
        })
        ->get();
        
        // Fetch original compliance data to compare with the requests
        $requestsWithCompliance = $requests->map(function ($request) use ($departments) {
            $originalCompliance = Compliance::find($request->compliance_id);
            
            // Decode changes JSON if necessary
            $changes = json_decode($request->changes, true);

            return [
                'request' => $request,
                'originalCompliance' => $originalCompliance,
                'changes' => $changes,
                'departments' => $departments // Pass departments mapping
            ];
        });
    
        return view('admin.requests', [
            'requestsWithCompliance' => $requestsWithCompliance,
        ]);
    }

    public function approveRequest($id)
    {
        
        $request = ComplianceRequest::find($id);

        // Handle based on action type
        if ($request->action == 'add') {
            Compliance::create(json_decode($request->changes, true));
        } elseif ($request->action == 'edit') {
            $compliance = Compliance::find($request->compliance_id);
            $compliance->update(json_decode($request->changes, true));
        } elseif ($request->action == 'delete') {
            $compliance = Compliance::find($request->compliance_id);
            $compliance->delete();
        }

        // Mark the request as approved
        $request->approved = true;
        $request->save();

        return redirect()->back()->with('message', 'Request approved.');
    }

    public function cancelRequest($id)
    {
        $request = ComplianceRequest::find($id);
        
        if($request) {
            $request->approved = 2; // 2 means canceled
            $request->save();

            return response()->json(['message' => 'Request cancelled successfully']);
        } else {
            return response()->json(['error' => 'Request not found'], 404);
        }
    }








    // COMPLIANCE DATA MANIPULATION
    public function showAllCompliances()
    {
        // dd('ok');

        $compliances = Compliance::all();
        $monthlyProjections = [];

        $currentMonth = Carbon::now()->startOfMonth();

        foreach ($compliances as $compliance) {

            $referenceDate = Carbon::parse($compliance->reference_date);
            $startWorkingOn = $compliance->start_working_on; // Numeric value from 'Start Working On' table

            if ($referenceDate->lt($currentMonth)) {
                $referenceDate = $currentMonth;
            }

            // Loop through 12 months
            for ($i = 0; $i < 12; $i++) {
                // Adjust the reference date for each month by adding 30 days
                $currentReferenceDate = $referenceDate->copy()->addDays(30 * $i); // Add 30 days for each iteration

                // Apply the "Start Working On" logic (subtracting based on numeric value)
                switch ($startWorkingOn) {
                    case 1:
                        $adjustedDate = $currentReferenceDate->subWeeks(1); // 1 week before
                        break;
                    case 2:
                        $adjustedDate = $currentReferenceDate->subWeeks(2); // 2 weeks before
                        break;
                    case 3:
                        $adjustedDate = $currentReferenceDate->subMonth();  // 1 month before
                        break;
                    case 4:
                        $adjustedDate = $currentReferenceDate->subMonths(2); // 2 months before
                        break;
                    case 5:
                        $adjustedDate = $currentReferenceDate->subMonths(3); // 3 months before
                        break;
                    case 6:
                        $adjustedDate = $currentReferenceDate->subMonths(4); // 4 months before
                        break;
                    default:
                        $adjustedDate = $currentReferenceDate; // No change if value is invalid
                }

                $displayDate = $adjustedDate->copy()->startOfMonth();

                $monthYear = $adjustedDate->format('F Y'); // e.g., 'October 2024'
                $monthlyProjections[$monthYear][] = [
                    'name' => $compliance->compliance_name,
                    'adjusted_date' => $adjustedDate->format('Y-m-d'),
                    'display_date' => $displayDate->format('Y-m-d'),
                ];
            }
        }

        return view('components.projection', [
            'monthlyProjections' => $monthlyProjections
        ]);
    }

    // Change Numeric to DepartmentName
    public function getDepartmentName($departmentId, $departments) {
        return $departments[$departmentId - 1]['department_name'] ?? 'Unknown Department';
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

    public function projections(Request $request)
    {
        // Fetch all compliances
        // $compliances = Compliance::all();
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

        // Current URI
        $currentRouteName = Route::currentRouteName();

        // Check which view is being requested
        if ($currentRouteName === 'overview') { // Change this to your actual route
            // Get current month deadlines
            $currentMonth = Carbon::now()->format('Y-m'); // Format: YYYY-MM
            $currentMonthDeadlines = array_filter($result, function($item) use ($currentMonth) {
                return Carbon::parse($item['deadline'])->format('Y-m') === $currentMonth;
            });

            return view('components.overview', ['currentMonthDeadlines' => $currentMonthDeadlines]);
        }

        // Pass the results to the Blade view
        return view('components.projection', compact('groupedResults'));

    }

}