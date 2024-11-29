<?php

namespace App\Http\Controllers;

use App\Mail\ComplianceReminder;
use App\Models\MonthlyCompliance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    // public function sendMonthlyReminders()
    // {
    //     // Current month start and end
    //     $startOfMonth = Carbon::now()->startOfMonth();
    //     $endOfMonth = Carbon::now()->endOfMonth();
    //     $today = Carbon::now();

    //     // Filter conditions
    //     $monthlyCompliances = MonthlyCompliance::when(
    //         Auth::user()->department_id == 1 || Auth::user()->department_id == 2 || 
    //         in_array(Auth::user()->role_id, [1, 2, 3]),
    //         function ($query) {
    //             // If user is in allowed department/role, show all compliances
    //             return $query;
    //         },
    //         function ($query) {
    //             // Otherwise, filter by the user's department
    //             return $query->where('department_id', Auth::user()->department_id);
    //         }
    //     )
    //     ->where(function ($query) use ($startOfMonth, $endOfMonth, $today) {
    //         // Filter by computed_start_date and computed_deadline
    //         $query->whereBetween('computed_start_date', [$startOfMonth, $endOfMonth])
    //             ->orWhereBetween('computed_deadline', [$startOfMonth, $endOfMonth])
    //             ->orWhere('computed_deadline', '<', $today); // Include overdue items
    //     })
    //     ->get()
    //     ->map(function ($compliance) use ($today) {
    //         // Parse the computed_deadline
    //         $deadline = Carbon::parse($compliance->computed_deadline);
            
    //         // Calculate days left (consider time of day differences)
    //         $compliance->days_left = (int) $today->diffInDays($deadline, false);
        
    //         // Adjust if needed for a more accurate "days remaining"
    //         if ($deadline->isPast() && !$deadline->isSameDay($today)) {
    //             $compliance->days_left -= 1; // To account for partial overlap
    //         }
        
    //         return $compliance;
    //     });

    //     // Send an email using the 'monthly' type for testing
    //     Mail::to('your_email@example.com')->send(new ComplianceReminder(
    //         $monthlyCompliances, // Pass the array of monthly compliance objects
    //         'Monthly Compliance Reminder',
    //         'monthly' // Type of reminder
    //     ));

    //     return 'Email sent successfully!';

    //     // return view('emails.monthly-compliance-reminder', compact('monthlyCompliances'));
    // }

    // public function sendWeeklyReminders()
    // {
    //     // Current month start and end
    //     $startOfMonth = Carbon::now()->startOfMonth();
    //     $endOfMonth = Carbon::now()->endOfMonth();
    //     $today = Carbon::now();

    //     // Filter conditions
    //     $monthlyCompliances = MonthlyCompliance::when(
    //         Auth::user()->department_id == 1 || Auth::user()->department_id == 2 || 
    //         in_array(Auth::user()->role_id, [1, 2, 3]),
    //         function ($query) {
    //             // If user is in allowed department/role, show all compliances
    //             return $query;
    //         },
    //         function ($query) {
    //             // Otherwise, filter by the user's department
    //             return $query->where('department_id', Auth::user()->department_id);
    //         }
    //     )
    //     ->where(function ($query) use ($startOfMonth, $endOfMonth, $today) {
    //         // Filter by computed_start_date and computed_deadline
    //         $query->whereBetween('computed_start_date', [$startOfMonth, $endOfMonth])
    //             ->orWhereBetween('computed_deadline', [$startOfMonth, $endOfMonth])
    //             ->orWhere('computed_deadline', '<', $today); // Include overdue items
    //     })
    //     ->get()
    //     ->map(function ($compliance) use ($today) {
    //         // Parse the computed_deadline
    //         $deadline = Carbon::parse($compliance->computed_deadline);
            
    //         // Calculate days left (consider time of day differences)
    //         $compliance->days_left = (int) $today->diffInDays($deadline, false);
        
    //         // Adjust if needed for a more accurate "days remaining"
    //         if ($deadline->isPast() && !$deadline->isSameDay($today)) {
    //             $compliance->days_left -= 1; // To account for partial overlap
    //         }
        
    //         return $compliance;
    //     });

    //     // Send an email using the 'monthly' type for testing
    //     Mail::to('your_email@example.com')->send(new ComplianceReminder(
    //         $monthlyCompliances, // Pass the array of monthly compliance objects
    //         'Weekly Compliance Reminder',
    //         'weekly' // Type of reminder
    //     ));

    //     return 'Email sent successfully!';

    //     // return view('emails.weekly-compliance-reminder', compact('monthlyCompliances'));
    // }

    // public function sendComplianceDeadline()
    // {
    //     // Get the current user's department and role
    //     $monthlyCompliances = MonthlyCompliance::query()
    //         ->when(
    //             in_array(Auth::user()->role_id, [1, 2, 3]) && in_array(Auth::user()->department_id, [1, 2]),
    //             function ($query) {
    //                 // Show all compliances for specific roles and departments
    //             },
    //             function ($query) {
    //                 // Restrict to the current user's department
    //                 $query->where('department_id', Auth::user()->department_id);
    //             }
    //         )
    //         ->whereDate('computed_deadline', now()->addDays(5)->toDateString())
    //         ->get();

        
    //     Mail::to('your_email@example.com')->send(new ComplianceReminder(
    //         $monthlyCompliances, // Pass the array of monthly compliance objects
    //         'Deadline Compliance Reminder',
    //         'deadline' // Type of reminder
    //     ));

    //     return 'Email sent successfully!';

    //     // Pass the filtered compliances to the blade view
    //     // return view('emails.deadline-compliance-reminder', compact('monthlyCompliances'));
    // }

    // public function sendComplianceTodayDeadline()
    // {
        

    //     return view('emails.today-compliance-reminder');
    // }


}
