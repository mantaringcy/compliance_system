<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\ComplianceRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    public function compose(View $view)
    {
        // Fetch departments
        $departments = Department::all()->pluck('department_name', 'id')->toArray();

        // Get the logged-in user's department_id (assuming it's stored in the authenticated user)
        $userDepartmentId = Auth::user()->department_id;

        // Fetch only the compliance requests for the user's department, excluding approved ones
        if ($userDepartmentId == 1) {
            $requests = ComplianceRequest::where('approved', false)->get();
        } else {
            // Filter compliances based on the user's department
            $requests = ComplianceRequest::where('approved', false)
                ->whereJsonContains('changes->department_id', $userDepartmentId)
                ->get();
        }

        // Count all requests
        $totalRequestsCount = $requests->count();

        $view->with('totalRequestsCount', $totalRequestsCount);
    }
}