<?php

namespace App\Console\Commands;

use App\Mail\ComplianceReminder;
use App\Models\MonthlyCompliance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendComplianceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-compliance-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    
    protected $description = 'Send compliance reminders to users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Case 1: Start of every month
        if (now()->isStartOfMonth()) {
            $this->sendMonthlyComplianceReminders();
        }

        // Case 2: Every Friday
        if (now()->isFriday()) {
            $this->sendWeeklyComplianceReminders();
        }

        // Case 3: Five days before computed_deadline
        $this->sendTodayDeadlineReminders();
        $this->sendDeadlineReminders();

        $this->info('Compliance reminders processed successfully!');
    }

    /**
     * Send monthly compliance reminders.
     */
    // private function sendMonthlyComplianceReminders()
    // {
    //     $compliances = MonthlyCompliance::whereMonth('computed_deadline', now()->month)
    //         ->whereYear('computed_deadline', now()->year)
    //         ->get();

    //     $this->sendReminders($compliances, 'Monthly Compliance Reminder', 'monthly');
    // }

    private function sendMonthlyComplianceReminders()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $today = Carbon::now();

        // Get all users to send reminders
        $users = User::all();

        foreach ($users as $user) {
            // Apply role/department-specific filtering
            $monthlyCompliances = MonthlyCompliance::query()
                ->when(
                    in_array($user->department_id, [1, 2]) || in_array($user->role_id, [1, 2, 3]),
                    function ($query) {
                        return $query; // Show all compliances
                    },
                    function ($query) use ($user) {
                        return $query->where('department_id', $user->department_id); // Filter by department
                    }
                )
                ->where(function ($query) use ($startOfMonth, $endOfMonth, $today) {
                    // Filter by computed_start_date and computed_deadline
                    $query->whereBetween('computed_start_date', [$startOfMonth, $endOfMonth])
                        ->orWhereBetween('computed_deadline', [$startOfMonth, $endOfMonth])
                        ->orWhere('computed_deadline', '<', $today); // Include overdue items
                })
                ->get()
                ->map(function ($compliance) use ($today) {
                    // Calculate days left
                    $deadline = Carbon::parse($compliance->computed_deadline);
                    $compliance->days_left = (int) $today->diffInDays($deadline, false);

                    // Adjust for past deadlines
                    if ($deadline->isPast() && !$deadline->isSameDay($today)) {
                        $compliance->days_left -= 1;
                    }

                    return $compliance;
                });

            // Send email only if there are compliances
            if ($monthlyCompliances->isNotEmpty()) {
                Mail::to($user->email)->send(new ComplianceReminder(
                    $monthlyCompliances->toArray(),
                    'Monthly Compliance Reminder',
                    'monthly'
                ));
            }
        }
    }


    /**
     * Send weekly compliance reminders.
     */
    // private function sendWeeklyComplianceReminders()
    // {
    //     $compliances = MonthlyCompliance::whereMonth('computed_deadline', now()->month)
    //         ->whereYear('computed_deadline', now()->year)
    //         ->get();

    //     $this->sendReminders($compliances, 'Weekly Compliance Reminder', 'weekly');
    // }

    private function sendWeeklyComplianceReminders()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $today = Carbon::now();

        // Get all users to send reminders
        $users = User::all();

        foreach ($users as $user) {
            // Apply role/department-specific filtering
            $monthlyCompliances = MonthlyCompliance::query()
                ->when(
                    in_array($user->department_id, [1, 2]) || in_array($user->role_id, [1, 2, 3]),
                    function ($query) {
                        return $query; // Show all compliances
                    },
                    function ($query) use ($user) {
                        return $query->where('department_id', $user->department_id); // Filter by department
                    }
                )
                ->where(function ($query) use ($startOfMonth, $endOfMonth, $today) {
                    // Filter by computed_start_date and computed_deadline
                    $query->whereBetween('computed_start_date', [$startOfMonth, $endOfMonth])
                        ->orWhereBetween('computed_deadline', [$startOfMonth, $endOfMonth])
                        ->orWhere('computed_deadline', '<', $today); // Include overdue items
                })
                ->get()
                ->map(function ($compliance) use ($today) {
                    // Calculate days left
                    $deadline = Carbon::parse($compliance->computed_deadline);
                    $compliance->days_left = (int) $today->diffInDays($deadline, false);

                    // Adjust for past deadlines
                    if ($deadline->isPast() && !$deadline->isSameDay($today)) {
                        $compliance->days_left -= 1;
                    }

                    return $compliance;
                });

            // Send email only if there are compliances
            if ($monthlyCompliances->isNotEmpty()) {
                Mail::to($user->email)->send(new ComplianceReminder(
                    $monthlyCompliances->toArray(),
                    'Weekly Compliance Reminder',
                    'weekly'
                ));
            }
        }
    }

    /**
     * Send reminders for items 5 days before their computed_deadline.
     */
    // private function sendDeadlineReminders()
    // {
    //     $compliances = MonthlyCompliance::whereDate('computed_deadline', now()->addDays(5))->get();

    //     $this->sendReminders($compliances, 'Upcoming Compliance Deadline Reminder', 'deadline');
    // }

    private function sendDeadlineReminders()
    {
        // Get the current date and calculate the date 5 days from now
        $today = Carbon::now();
        $deadlineDate = $today->addDays(5)->toDateString();

        // Get all users to send reminders
        $users = User::all();

        foreach ($users as $user) {
            // Apply role/department-specific filtering
            $monthlyCompliances = MonthlyCompliance::query()
                ->when(
                    in_array($user->role_id, [1, 2, 3]) && in_array($user->department_id, [1, 2]),
                    function ($query) {
                        return $query; // Show all compliances for specific roles and departments
                    },
                    function ($query) use ($user) {
                        return $query->where('department_id', $user->department_id); // Restrict to the current user's department
                    }
                )
                ->whereDate('computed_deadline', $deadlineDate) // Filter by the date 5 days from now
                ->get()
                ->map(function ($compliance) use ($today) {
                    // Calculate the days left
                    $deadline = Carbon::parse($compliance->computed_deadline);
                    $compliance->days_left = (int) $today->diffInDays($deadline, false);

                    // Adjust for past deadlines
                    if ($deadline->isPast() && !$deadline->isSameDay($today)) {
                        $compliance->days_left -= 1;
                    }

                    return $compliance;
                });

            // Send email only if there are compliances
            if ($monthlyCompliances->isNotEmpty()) {
                Mail::to($user->email)->send(new ComplianceReminder(
                    $monthlyCompliances->toArray(),
                    'Upcoming Compliance Deadline Reminder',
                    'deadline'
                ));
            }
        }

        return '5-day deadline reminders sent successfully!';
    }

    /**
     * Send reminders for today's deadline.
     */
    private function sendTodayDeadlineReminders()
    {
        // Get the current date as a Carbon instance
        $today = Carbon::now();
    
        // Get all users to send reminders
        $users = User::all();
    
        foreach ($users as $user) {
            // Apply role/department-specific filtering
            $monthlyCompliances = MonthlyCompliance::query()
                ->when(
                    in_array($user->role_id, [1, 2, 3]) && in_array($user->department_id, [1, 2]),
                    function ($query) {
                        return $query; // Show all compliances for specific roles and departments
                    },
                    function ($query) use ($user) {
                        return $query->where('department_id', $user->department_id); // Restrict to the current user's department
                    }
                )
                ->whereDate('computed_deadline', $today->toDateString()) // Filter for today's deadline
                ->get()
                ->map(function ($compliance) use ($today) {
                    // Parse the computed_deadline
                    $deadline = Carbon::parse($compliance->computed_deadline);
                    
                    // Calculate days left (should be 0 for today's deadline)
                    $compliance->days_left = (int) $today->diffInDays($deadline, false);
    
                    // Adjust if needed for a more accurate "days remaining"
                    if ($deadline->isPast() && !$deadline->isSameDay($today)) {
                        $compliance->days_left -= 1;
                    }
    
                    return $compliance;
                });
    
            // Send email only if there are compliances for today
            if ($monthlyCompliances->isNotEmpty()) {
                Mail::to($user->email)->send(new ComplianceReminder(
                    $monthlyCompliances->toArray(),
                    'Today Compliance Deadline Reminder',
                    'today_deadline'
                ));
            }
        }
    
        return 'Today deadline reminders sent successfully!';
    }

    /**
     * Send email reminders for the given compliances.
     *
     * @param \Illuminate\Database\Eloquent\Collection $compliances
     * @param string $subject
     */
    private function sendReminders($compliances, $subject, $emailType)
    {
        if ($compliances->isEmpty()) {
            return;
        }

        $emailRecipients = ['user@example.com']; // Replace with dynamic logic for recipient emails

        foreach ($emailRecipients as $email) {
            Mail::to($email)->send(new ComplianceReminder($compliances->toArray(), $subject, $emailType));
        }
    }
}
