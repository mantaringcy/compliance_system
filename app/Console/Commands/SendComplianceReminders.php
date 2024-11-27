<?php

namespace App\Console\Commands;

use App\Mail\ComplianceReminder;
use App\Models\MonthlyCompliance;
use Illuminate\Console\Command;
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
        $this->sendDeadlineReminders();

        $this->info('Compliance reminders processed successfully!');
    }

    /**
     * Send monthly compliance reminders.
     */
    private function sendMonthlyComplianceReminders()
    {
        $compliances = MonthlyCompliance::whereMonth('computed_deadline', now()->month)
            ->whereYear('computed_deadline', now()->year)
            ->get();

        $this->sendReminders($compliances, 'Monthly Compliance Reminder', 'monthly');
    }


    /**
     * Send weekly compliance reminders.
     */
    private function sendWeeklyComplianceReminders()
    {
        $compliances = MonthlyCompliance::whereMonth('computed_deadline', now()->month)
            ->whereYear('computed_deadline', now()->year)
            ->get();

        $this->sendReminders($compliances, 'Weekly Compliance Reminder', 'weekly');
    }

    /**
     * Send reminders for items 5 days before their computed_deadline.
     */
    private function sendDeadlineReminders()
    {
        $compliances = MonthlyCompliance::whereDate('computed_deadline', now()->addDays(5))->get();

        $this->sendReminders($compliances, 'Upcoming Compliance Deadline Reminder', 'deadline');
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
