<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\ComplianceLog;
use App\Models\ComplianceRequest;
use App\Models\Department;
use App\Models\User;
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

    // REQUEST FOR CHANGE 
    public function reviewRequests()
    {
        // Fetch departments
        $departments = Department::all()->pluck('id', 'department_name')->toArray();

        // Get the logged-in user's department_id (assuming it's stored in the authenticated user)
        $userDepartmentId = Auth::user()->department_id;

        

        // Check if the user is from department 1, in which case show all compliances
        if ($userDepartmentId == 1) {
            $requests = ComplianceRequest::where('approved', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        } else {
            // Filter compliances based on the user's department
            $requests = ComplianceRequest::where('approved', 0)
                ->orderBy('created_at', 'desc')
                ->whereJsonContains('changes->department_id', $userDepartmentId)
                ->get();
        }
      
        // Fetch original compliance data to compare with the requests
        $requestsWithCompliance = $requests->map(function ($request) use ($departments) {
            $originalCompliance = Compliance::find($request->compliance_id);

            // dd($request);

            // dd($request);

            // dd($originalCompliance);
            
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
        // $changes = array_merge($request->changes);

        // Handle based on action type
        if ($request->action == 'add') {
            Compliance::create(json_decode($request->changes, true));
            $newCompliance = json_decode($request->changes, true);


            // Log the add
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'add/approved',
                // 'compliance_id' => $request->id,
                'department_id' => $newCompliance['department_id'],
                'changes' => json_encode($newCompliance)
            ]);

        } else if ($request->action == 'edit') {
            $compliance = Compliance::find($request->compliance_id);
            $oldCompliance = $compliance->toArray(); // Capture old data for logging
            
            $compliance->update(json_decode($request->changes, true));

            $newCompliance = json_decode($request->changes, true);

            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'edit/approved',
                'compliance_id' => $compliance->id,
                'department_id' => $newCompliance['department_id'],
                'changes' => json_encode([
                    'old' => $oldCompliance,  // Convert old compliance to array
                    'new' => $newCompliance, // Store new data excluding _token
                ]),
            ]);
        } else if ($request->action == 'delete') {
            $compliance = Compliance::find($request->compliance_id);

            // Log the deletion
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete/approved',
                'compliance_id' => $compliance->id,
                'department_id' => $compliance->department_id,
                'changes' => json_encode($request),
            ]);

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

        $newCompliance = json_decode($request->changes, true);

        
        if($request) {
            $request->approved = 2; // 2 means canceled
            $request->save();

            // Log the add
            ComplianceLog::create([
                'user_id' => Auth::id(),
                'action' => 'cancelled',
                // 'compliance_id' => $request->id,
                'department_id' => $newCompliance['department_id'],
                'changes' => json_encode($newCompliance)
            ]);

            return response()->json(['message' => 'Request cancelled successfully']);
        } else {
            return response()->json(['error' => 'Request not found'], 404);
        }
    }

    
    // LOGS
    public function showAllLogs(Request $request)
    {
      
        $logs = ComplianceLog::orderBy('created_at', 'desc')->get()->map(function ($log) {
            // Get the compliance name, including soft-deleted records
            $compliance = Compliance::withTrashed()->find($log->compliance_id);
            $complianceName = $compliance ? $compliance->compliance_name : 'Unknown Compliance';
            $departments = Department::all()->pluck('department_name', 'id')->toArray();

        
            // Add the compliance name to the log entry for easier access later
            $log->compliance_name = $complianceName;
        
            return $log;
        });

        return view('components.logs-sample', compact('logs'));

    }

    public function getAllLogs(Request $request)
    {
        // Get the current user
        $currentUser = Auth::user();
        $departmentId = $currentUser->department_id;
        $roleId = $currentUser->role_id;
        $userId = $currentUser->id;

        // Initialize the base query for logs
        $logsQuery = ComplianceLog::query();

       // Check if the user can see all logs (department 1 or 2 with roles 1, 2, or 3)
        if (in_array($departmentId, [1, 2]) && in_array($roleId, [1, 2, 3])) {
            // Show all logs for department 1 or 2 with allowed roles
        } else {
            // For any other department (3 to 15), show only the logs that match the user's department_id
            $logsQuery->where('department_id', $departmentId);
            // dd($userId);
        }

        // Get the logs in descending order and map them
        $logs = $logsQuery->orderBy('created_at', 'desc')->get()->map(function ($log) {
            // Get the compliance name, including soft-deleted records
            $compliance = Compliance::withTrashed()->find($log->compliance_id);
            $complianceName = $compliance ? $compliance->compliance_name : 'Unknown Compliance';
            $departments = Department::all()->pluck('department_name', 'id')->toArray();

            // Add the compliance name to the log entry for easier access later
            $log->compliance_name = $complianceName;

            return $log;
        });

        if ($request->ajax()) {
            return DataTables::of($logs)
            ->addColumn('id', function ($log) {
                return $log->id;
            })
            ->addColumn('date', function ($log) {
                return \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A');
            })
            ->addColumn('action', function ($log) {
                $changesData = json_decode($log->changes, true);

                switch($log->action) {
                    case 'add':
                        return '<span class="badge badge-blue">' . Str::upper('Add') . '</span>';
                        break;
                    case 'edit':
                        return '<span class="badge badge-green">' . Str::upper('Edit') . '</span>';
                        break;
                    case 'delete':
                        return '<span class="badge badge-red">' . Str::upper('Delete') . '</span>';
                        break;
                        case 'add/approval':
                            return '<span class="badge badge-blue-light">' . Str::upper('Add - Pending') . '</span>';
                        break;
                    case 'edit/approval':
                        return '<span class="badge badge-green-light">' . Str::upper('Edit - Pending') . '</span>';
                        break;
                    case 'delete/approval':
                        return '<span class="badge badge-red-light">' . Str::upper('Delete - Pending') . '</span>';
                        break;
                    case 'add/approved':
                        return '<span class="badge badge-blue-light">' . Str::upper('Added') . '</span>';
                        break;
                    case 'edit/approved':
                        return '<span class="badge badge-green-light">' . Str::upper('Edited') . '</span>';
                        break;
                    case 'delete/approved':
                        return '<span class="badge badge-red-light">' . Str::upper('Deleted') . '</span>';
                        break;
                    case 'cancelled':
                        if (isset($changesData['_method']) && $changesData['_method'] == "DELETE")
                            return '<span class="badge badge-light">' . Str::upper('Cancelled - Deletion') . '</span>';
                        elseif (isset($changesData['_method']) && $changesData['_method'] == "PUT") {
                            return '<span class="badge badge-light">' . Str::upper('Cancelled - Editing') . '</span>';
                        } else {
                            return '<span class="badge badge-light">' . Str::upper('Cancelled - Addition') . '</span>';
                        }
                        break;
                    default: 
                        return '<span class="badge badge-blue">' . Str::upper($log->action) . '</span>';
                }
                
            })
            ->addColumn('user', function ($log) {
                return $log->user->username;
            })
            ->addColumn('compliance_name', function ($log) {
                $changesData = json_decode($log->changes, true);

                if ($log->action == 'add/approved' || $log->action == 'add/approval' || $log->action == 'cancelled') {
                    return $changesData['compliance_name'];
                }
                return $log->compliance_name;
            })
            ->addColumn('changes', function ($log) {
                $changesData = json_decode($log->changes, true);

                // switch($log->action) {
                //     case 'add':
                //         // return 'Additon of Compliance ' . $log->compliance_name;
                //         return 'Initiated the addition of ' . '<span class="fst-italic">' . $log->compliance_name . '</span>';
                //         break;
                //     case 'edit':
                //         return 'Updated ' . '<span class="fst-italic">' . $log->compliance_name . '</span>' . ' details';
                //         break;
                //     case 'delete':
                //         return 'Initiated the deletion of ' . '<span class="fst-italic">' . $log->compliance_name . '</span>';
                //         break;
                //     case 'add/approval':
                //         return 'Requested the addition of ' . '<span class="fst-italic">' . $changesData['compliance_name'] . '</span>';
                //         break;
                //     case 'edit/approval':
                //         return 'Requested an edit for ' . '<span class="fst-italic">' . $log->compliance_name . '</span>';
                //         break;
                //     case 'delete/approval':
                //         return 'Request the deletion of ' . '<span class="fst-italic">' . $log->compliance_name . '</span>';
                //         break;
                //     case 'add/approved':
                //         return 'Approved the addition of ' . '<span class="fst-italic">' . $changesData['compliance_name'] . '</span>';
                //         return '<span class="badge badge-blue-light">' . Str::upper('Added') . '</span>';
                //         break;
                //     case 'edit/approved':
                //         return 'Approved the edit of ' . '<span class="fst-italic">' . $log->compliance_name . '</span>';
                //         break;
                //     case 'delete/approved':
                //         return 'Approved the deletion of ' . '<span class="fst-italic">' . $log->compliance_name . '</span>';
                //         break;
                //     case 'cancelled':
                //         if (isset($changesData['_method']) && $changesData['_method'] == "DELETE")
                //             return 'Cancelled the addition of ' . '<span class="fst-italic">' . $changesData['compliance_name'] . '</span>';
                //         elseif (isset($changesData['_method']) && $changesData['_method'] == "PUT") {
                //             return 'Cancelled the edit of ' . '<span class="fst-italic">' . $changesData['compliance_name'] . '</span>';
                //         } else {
                //             return 'Cancelled the deletion of ' . '<span class="fst-italic">' . $changesData['compliance_name'] . '</span>';
                //         }
                //         break;
                //     default: 
                //         return '<span class="badge badge-blue">' . Str::upper($log->action) . '</span>';
                // }
                switch($log->action) {
                    case 'add':
                        // return 'Additon of Compliance ' . $log->compliance_name;
                        return 'Initiated the addition of ' . $log->compliance_name;
                        break;
                    case 'edit':
                        return 'Updated ' . $log->compliance_name . ' details';
                        break;
                    case 'delete':
                        return 'Initiated the deletion of ' . $log->compliance_name;
                        break;
                    case 'add/approval':
                        return 'Requested the addition of ' . $changesData['compliance_name'];
                        break;
                    case 'edit/approval':
                        return 'Requested an edit for ' . $log->compliance_name;
                        break;
                    case 'delete/approval':
                        return 'Request the deletion of ' . $log->compliance_name;
                        break;
                    case 'add/approved':
                        return 'Approved the addition of ' . $changesData['compliance_name'];
                        return '<span class="badge badge-blue-light">' . Str::upper('Added');
                        break;
                    case 'edit/approved':
                        return 'Approved the edit of ' . $log->compliance_name;
                        break;
                    case 'delete/approved':
                        return 'Approved the deletion of ' . $log->compliance_name;
                        break;
                    case 'cancelled':
                        if (isset($changesData['_method']) && $changesData['_method'] == "DELETE")
                            return 'Cancelled the addition of ' . $changesData['compliance_name'];
                        elseif (isset($changesData['_method']) && $changesData['_method'] == "PUT") {
                            return 'Cancelled the edit of ' . $changesData['compliance_name'];
                        } else {
                            return 'Cancelled the deletion of ' . $changesData['compliance_name'];
                        }
                        break;
                    default: 
                        return '<span class="badge badge-blue">' . Str::upper($log->action);
                }
                // $oldData = $changesData['old'] ?? [];
                // $newData = $changesData['new'] ?? [];
    
                // Exclude unnecessary fields
                // unset($oldData['id'], $newData['id'], $newData['_token'], $newData['complianceId'], $newData['_method']);
    
                // // // Key mappings
                // $keyMapping = [
                //     'compliance_name' => 'Compliance Name',
                //     'department_id' => 'Department',
                //     'reference_date' => 'Reference Date',
                //     'frequency' => 'Frequency',
                //     'start_working_on' => 'Start Working On',
                //     'submit_on' => 'Submit On',
                //     'action' => 'Action',
                //     'changes' => 'Changes',
                //     'user_id' => 'User ID'
                    
                // ];
    
                // // Calculate changes
                // $changes = array_diff_assoc($newData, $oldData);
    
                // // // Format output
                // $output = '';
                // foreach ($changes as $key => $newValue) {
                //     $oldValue = $oldData[$key] ?? 'N/A';
                //     $output .= "<strong>{$keyMapping[$key]}:</strong> Changed from {$oldValue} to {$newValue}<br>";
                // }
    
                // echo $changesData;
                // if ($log->action == 'add/approved' || $log->action == 'add/approval' || $log->action == 'cancelled') {
                //     return $changesData['compliance_name'];
                // }
                // return $log->compliance_name;
            })
            
            // ->rawColumn(['actions', 'changes'])
            ->escapeColumns([]) // Disable HTML escaping for all columns
            ->make(true);
        }


        // foreach ($logs as $log) {
        //     echo "Action: {$log->action}, Compliance: {$log->compliance_name}";
        // }

        // dd($logs);

        // foreach ($logs as $log) {
        //     // dd($log->user->username);
        //     echo $log->compliance->name; // or any other field from the Compliance model
        // }
        // dd($logs);

        return view('components.logs');
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