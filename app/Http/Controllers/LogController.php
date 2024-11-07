<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\ComplianceLog;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class LogController extends Controller
{
    // LOGS
    public function index(Request $request)
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

        return view('components.logs');
    }

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
 
}
