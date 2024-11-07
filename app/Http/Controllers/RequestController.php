<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\ComplianceLog;
use App\Models\ComplianceRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(Request $request)
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

            // Decode changes JSON if necessary
            $changes = json_decode($request->changes, true);

            return [
                'request' => $request,
                'originalCompliance' => $originalCompliance,
                'changes' => $changes,
                'departments' => $departments // Pass departments mapping
            ];
        });

        if ($request->ajax()) {
            return DataTables::of($requestsWithCompliance)
            ->addColumn('id', function ($requestCompliance) {
                $id = $requestCompliance['originalCompliance']->id ?? '#';

                return $id;
            })
            ->addColumn('compliance_name', function ($requestCompliance) {
                return $requestCompliance['originalCompliance']->compliance_name ?? $requestCompliance['changes']['compliance_name'];
            })
            ->addColumn('department_name', function ($requestCompliance) {
                if (isset($requestCompliance['originalCompliance']->department->department_name)) {
                    return $requestCompliance['originalCompliance']->department->department_name;
                } else {
                    $departmentId = $requestCompliance['changes']['department_id'];
                    $department = Department::find($departmentId);

                    return $department->department_name;
                }
            })
            ->addColumn('request_type', function ($requestCompliance) {
                $action = $requestCompliance['request']->action;

                switch ($action) {
                    case 'add':
                        return '<span class="badge badge-blue-light">' . 'ADDITION' . '</span>';
                        break;
                    case 'edit':
                        return '<span class="badge badge-green-light">' . 'EDITING' . '</span>';
                        break;
                    case 'delete':
                        return '<span class="badge badge-red-light">' . 'DELETION' . '</span>';
                        break;
                    default:
                        return '<span class="badge badge-light">' . 'Tag Not Recognized' . '</span>';
                }
            })
            ->addColumn('action', function ($requestCompliance) {
                $action = $requestCompliance['request']->action;

                $complianceId = $requestCompliance['request']['id'] ?? '#';
                $complianceName = $requestCompliance['originalCompliance']->compliance_name ?? 'Unknown Compliance';
                $complianceDepartment = $requestCompliance['originalCompliance']->department_id ?? 'Unknown Department';
                $complianceReferenceDate = $requestCompliance['originalCompliance']->reference_date ?? 'Unknown Reference Date';
                $complianceFrequency = $requestCompliance['originalCompliance']->frequency ?? 'Unknown Frequency';
                $complianceStartWorkingOn = $requestCompliance['originalCompliance']->start_working_on ?? 'Unknown Compliance Starting';
                $complianceSubmitOn = $requestCompliance['originalCompliance']->submit_on ?? 'Unknown Submit Compliance Submission';

                $newComplianceName = $requestCompliance['changes']['compliance_name'] ?? 'New Compliance Name';
                $newComplainceDepartment = $requestCompliance['changes']['department_id'] ?? 'New Compliance Department';
                $newComplianceReferenceDate = $requestCompliance['changes']['reference_date'] ?? 'New Compliance Reference Date';
                $newComplianceFrequency = $requestCompliance['changes']['frequency'] ?? 'New Compliance Frequency';
                $newComplianceStartWorkingOn = $requestCompliance['changes']['start_working_on'] ?? 'New Compliance Starting';
                $newComplianceSubmitOn = $requestCompliance['changes']['submit_on'] ?? 'New Compliance Submission';


                $addActionButton = '
                    <a href="#" 
                        class="view-btn add-request-compliance"
                        data-bs-toggle="modal" 
                        data-bs-target="#addRequestComplianceModal"
                        data-compliance-id="'. $complianceId .'"
                        data-compliance-name="'. $newComplianceName .'"
                        data-department-id="'. $newComplainceDepartment .'"
                        data-compliance-reference-date="'. $newComplianceReferenceDate .'"
                        data-compliance-frequency="'. $newComplianceFrequency .'"
                        data-compliance-start-working-on="'. $newComplianceStartWorkingOn .'"
                        data-compliance-submit-on="'. $newComplianceSubmitOn .'"
                    ><i class="fa-regular fa-square-plus"></i></a>
                ';

                $editActionButton = '
                    <a href="#" 
                        class="edit-btn edit-request-compliance"
                        data-bs-toggle="modal" 

                        data-compliance-id="'. $complianceId .'"
                        data-compliance-name="'. $complianceName .'"
                        data-department-id="'. $complianceDepartment .'"
                        data-compliance-reference-date="'. $complianceReferenceDate .'"
                        data-compliance-frequency="'. $complianceFrequency .'"
                        data-compliance-start-working-on="'. $complianceStartWorkingOn .'"
                        data-compliance-submit-on="'. $complianceSubmitOn .'"

                        data-new-compliance-name="'. $newComplianceName .'"
                        data-new-department-id="'. $newComplainceDepartment .'"
                        data-new-compliance-reference-date="'. $newComplianceReferenceDate .'"
                        data-new-compliance-frequency="'. $newComplianceFrequency .'"
                        data-new-compliance-start-working-on="'. $newComplianceStartWorkingOn .'"
                        data-new-compliance-submit-on="'. $newComplianceSubmitOn .'"
                        
                        data-bs-target="#editRequestComplianceModal"
                    ><i class="fa-regular fa-pen-to-square"></i></a>
                ';

                $deleteActionButton = '
                    <a href="#" 
                        class="delete-btn delete-request-compliance"
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteRequestComplianceModal"
                        data-compliance-id="'. $complianceId .'"
                        data-compliance-name="'. $complianceName .'"
                        data-department-id="'. $complianceDepartment .'"
                        data-compliance-reference-date="'. $complianceReferenceDate .'"
                        data-compliance-frequency="'. $complianceFrequency .'"
                        data-compliance-start-working-on="'. $complianceStartWorkingOn .'"
                        data-compliance-submit-on="'. $complianceSubmitOn .'"
                    ><i class="fa-regular fa-trash-can"></i></a>
                ';


                if ($action == 'add') {
                    return $addActionButton;
                } elseif ($action == 'edit') {
                    return $editActionButton;
                } elseif ($action == 'delete') {
                    return $deleteActionButton;
                }
            })
            ->escapeColumns([]) // Disable HTML escaping for all columns
            ->make(true);
        }
    
        return view('admin.requests');
    }

    public function approveRequest($id)
    {
        
        $request = ComplianceRequest::find($id);

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

        $complianceName = $newCompliance['compliance_name'];

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

            if ($request->action == 'add') {
                return response()->json([
                    'success' => true,
                    'action' => 'cancel_create_compliance',
                    'compliance_name' => $complianceName
                ]);
            } else if ($request->action == 'edit') {
                return response()->json([
                    'success' => true,
                    'action' => 'cancel_edit_compliance',
                    'compliance_name' => $complianceName
                ]);
            } else if ($request->action == 'delete') {
                return response()->json([
                    'success' => true,
                    'action' => 'cancel_delete_compliance',
                    'compliance_name' => $complianceName
                ]);
            }

        } else {
            return response()->json(['error' => 'Request not found'], 404);
        }
    }
}