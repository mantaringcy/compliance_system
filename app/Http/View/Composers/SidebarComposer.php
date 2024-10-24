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
        $requests = ComplianceRequest::where('approved', false)
        ->when(!in_array($userDepartmentId, [1]), function ($query) use ($userDepartmentId) {
            return $query->whereHas('compliance', function ($complianceQuery) use ($userDepartmentId) {
                $complianceQuery->where('department_id', $userDepartmentId);
            });
        })
        ->get();

        // Count all requests
        $totalRequestsCount = $requests->count();

        $view->with('totalRequestsCount', $totalRequestsCount);
    }
}