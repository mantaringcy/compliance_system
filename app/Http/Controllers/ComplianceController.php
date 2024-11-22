<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\ComplianceLog;
use App\Models\ComplianceRequest;
use App\Models\Department;
use App\Models\MonthlyCompliance;
use App\Models\User;
use App\Services\ComplianceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Hamcrest\Core\HasToString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ComplianceController extends Controller
{
    protected $complianceService;

     // Inject the ComplianceService into the controller
     public function __construct(ComplianceService $complianceService)
     {
         $this->complianceService = $complianceService;
     }


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

        // dd('ok');

        
        // Check if user is a super admin
        if (Auth::user()->role_id == 3) {
            // Directly create compliance if super admin
            $compliance = Compliance::create($fields);


            // Log the add
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'add',
                'compliance_id' => $compliance->id,
                'department_id' => $compliance->department_id,
                'changes' => json_encode($request->all())
            ]);

            return response()->json([
                'success' => true,
                'action' => 'create_compliance',
                'compliance_name' => $fields['compliance_name']
            ]);

        } else {
            // $compliance = Compliance::create($fields);

            // Log the add
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'add/approval',
                'compliance_id' => $request->id,
                'department_id' => $request->department_id,
                'changes' => json_encode($request->all())
            ]);

            // Store the request for super admin review
            ComplianceRequest::create([
                'user_id' => Auth::id(),
                'action' => 'add',
                // 'compliance_id' => 1,
                'changes' => json_encode($request->all()),
            ]);

            
            return response()->json([
                'success' => true,
                'action' => 'request_create_compliance',
                'compliance_name' => $fields['compliance_name']
            ]);
        }

     
        // return back()->with('success', 'Your post was created.');
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

        $oldCompliance = $compliance->toArray(); // Capture old data for logging


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


            // Log the changes
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'edit',
                'compliance_id' => $compliance->id,
                'department_id' => $compliance->department_id,
                'changes' => json_encode([
                    'old' => $oldCompliance,
                    'new' => $request->all(),
                ]),
            ]);

            return response()->json([
                'success' => true,
                'action' => 'edit_compliance'
            ]);

        } else {
            // Log the changes
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'edit/approval',
                'compliance_id' => $compliance->id,
                'department_id' => $compliance->department_id,
                'changes' => json_encode([
                    'old' => $oldCompliance,  // Convert old compliance to array
                    'new' => $request->all(), // Store new data excluding _token
                ]),
            ]);


            // Store the edit request for super admin review
            ComplianceRequest::create([
                'compliance_id' => $compliance->id,
                'user_id' => Auth::id(),
                'action' => 'edit',
                'changes' => json_encode($request->all()),
            ]);

            return response()->json([
                'success' => true,
                'action' => 'request_edit_compliance'
            ]);
        }
    }

    // Delete Compliance
    public function destroy($id, Request $request)
    {
        // Step 1: Find the Existing Record
        $compliance = Compliance::findOrFail($id); // This will throw a 404 if not found

        $departmentId = Auth::user()->department_id;
        $changes = array_merge($request->all(), ['department_id' => $departmentId, 'compliance_name' => $compliance->compliance_name]);


        if (Auth::user()->role_id == 3) {
            // Directly delete compliance if super admin
            $compliance->delete(); // Delete the record

            // Log the deletion
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'compliance_id' => $id,
                'department_id' => $compliance->department_id,
                'changes' => json_encode($request->all()),
            ]);

            return response()->json([
                'success' => true,
                'action' => 'delete_compliance',
                // 'message' => 'Compliance has been deleted successfully.'
            ]);

        } else {
            // Log the deletion
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete/approval',
                'compliance_id' => $id,
                'department_id' => $compliance->department_id,
                'changes' => json_encode([$request->all()]),
            ]);

            // Store the delete request for super admin review
            ComplianceRequest::create([
                'compliance_id' => $id,
                'user_id' => Auth::id(),
                'action' => 'delete',
                'changes' => json_encode($changes),
            ]);

            return response()->json([
                'success' => true,
                'action' => 'request_delete_compliance',
                // 'message' => 'Request for compliance deletion has been submitted.'
            ]);
        }        

        // Step 2: Authorize the Action
        // Gate::authorize('delete', $compliance); // Check if the user is authorized

   

       
        // Step 4: Redirect or Return a Response
        // return back()->with('success', 'Compliance deleted successfully.');
        // return response()->json(['message' => 'Compliance deleted successfully!'], 200);
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

   

    public function projections()
    {
        $projections = $this->complianceService->monthlyProjections();

        return $projections;

    }

    public function monthlyCompliances(Request $request)
    {
       $monthlyCompliance = $this->complianceService->monthlyCompliances();

       $complianceOverview = $this->complianceService->complianceOverview(Auth::user());


        // Retrieve departments from the database
        $departments = Department::all()->toArray(); 

        $user = Auth::user();

        // $departments = Department::pluck('department_name', 'id');
        
        // Check if the user's credentials match the criteria
        if (in_array($user->department_id, [1, 2]) && in_array($user->role_id, [1, 2, 3])) {
            // Show all $monthlyCompliances if the user meets the criteria
            $monthlyCompliances = MonthlyCompliance::all()->map(function ($compliance) use ($departments) {
                $compliance->days_difference = now()->startOfDay()->diffInDays(
                    \Carbon\Carbon::parse($compliance->computed_deadline)->startOfDay(),
                    false
                );
                $compliance->department_name = $this->getDepartmentName($compliance->department_id, $departments);
                return $compliance;
            });
        } else {
            // Filter by the current user's department
            $monthlyCompliances = MonthlyCompliance::where('department_id', $user->department_id)->get()->map(function ($compliance) use ($departments) {
                $compliance->days_difference = now()->startOfDay()->diffInDays(
                    \Carbon\Carbon::parse($compliance->computed_deadline)->startOfDay(),
                    false
                );
                $compliance->department_name = $this->getDepartmentName($compliance->department_id, $departments);
                return $compliance;
            });
        }

        // Sort the compliances:
        // - First by 'status' (completed at the bottom)
        // - Then by 'deadline' in ascending order
        $monthlyCompliances = $monthlyCompliances->sortBy(function($compliance) {
            // First, sort by 'status', completed should come last
            return $compliance['status'] == 'completed' ? 1 : 0;
        })->sortBy(function($compliance) {
            // Then, sort by 'deadline' (ascending)
            return Carbon::parse($compliance['deadline']);
        });

        return view('components.overview', [
            'monthlyCompliances' => $monthlyCompliances,
            'overviewData' => $complianceOverview,

        ]);
    }

}