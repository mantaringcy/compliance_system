<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ComplianceController extends Controller
{
    // Returns data to the Compliance List Module
    public function index(Request $request)
    {
        $complianceEntries = DB::table('compliances')->get(); // Fetch all entries

        if ($request->ajax()) {
            $compliances = Compliance::select('id', 'compliance_name', 'frequency', 'created_at');

            // dd($compliances);

            return DataTables::of($complianceEntries)
                ->addColumn('action', function($row){
                    $viewAnchor = '<a href="#" class="view-btn view-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewComplianceModal"
                            data-compliance-id="'.$row->id.'"
                            data-compliance-name="'.$row->compliance_name.'"
                            data-department-id="'.$row->department_id.'"
                            data-compliance-reference-date="'.$row->reference_date.'"
                            data-compliance-frequency="'.$row->frequency.'"
                            data-compliance-start-on="'.$row->start_on.'"
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
                            data-compliance-start-on="'.$row->start_on.'"
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

        foreach ($complianceEntries as $entry) {

            // $statusKey = (string) $entry->department_id;
            $entry->mapped_department = $department->get($entry->department_id, 'Unknown');


            // Map frequency value
            $statusKey = (string) $entry->frequency;
            $entry->mapped_frequency = Config::get('static_data.frequency.' . $statusKey, 'Unknown');
            
            // Map start_on value
            $statusKey = (string) $entry->start_on;
            $entry->mapped_startOn = Config::get('static_data.start_on.' . $statusKey, 'Unknown');

            // Map submit_on value
            $statusKey = (string) $entry->submit_on;
            $entry->mapped_submitOn = Config::get('static_data.submit_on.' . $statusKey, 'Unknown');

        }
 
        return view('components.compliance-list', compact('complianceEntries'));
        // return view('components.main', compact('complianceEntries'));

    }

    // Create Compliance
    public function store(Request $request)
    {

        // dd($request);
        // dd('ok');


        $fields = $request->validate([
            'compliance_name' => ['required'],
            'department_id' => ['required'],
            'reference_date' => ['required'],
            'frequency' => ['required', 'not_in:0,'],
            'start_on' => ['required', 'not_in:0,'],
            'submit_on' => ['required', 'not_in:0,']
        ]);


        $compliance = Compliance::create($fields);

        // return redirect()->withErrors($fields)->withInput();
        return back()->with('success', 'Your post was created.');
        // return view('components.compliance-list');

    }

    // Update Compliance
    public function update(Request $request, Compliance $compliance)
    {
        // dd('ok');
        // Gate::authorize('modify', $compliance);

        $fields = $request->validate([
            'compliance_name' => ['required'],
            'department_id' => ['required'],
            'reference_date' => ['required'],
            'frequency' => ['required'],
            'start_on' => ['required'],
            'submit_on' => ['required']
        ]);

        $compliance->update([
            'compliance_name' => $fields['compliance_name'],
            'department_id' => $fields['department_id'],
            'reference_date' => $fields['reference_date'],
            'frequency' => $fields['frequency'],
            'start_on' => $fields['start_on'],
            'submit_on' => $fields['submit_on']

        ]);
        return response()->json(['success' => 'Compliance updated successfully.']);


        // return back()->with('success', 'Your post was updated.');

        // dd($compliance);
    }

    // Delete Compliance
    public function destroy($id)
    {
        // Step 1: Find the Existing Record
        $compliance = Compliance::findOrFail($id); // This will throw a 404 if not found

        // Step 2: Authorize the Action
        // Gate::authorize('delete', $compliance); // Check if the user is authorized

        // Step 3: Delete the Record
        $compliance->delete(); // Delete the record

        // Step 4: Redirect or Return a Response
        return back()->with('success', 'Compliance deleted successfully.');
        // return response()->json(['message' => 'Compliance deleted successfully!'], 200);
    }


    public function showAllCompliances()
    {
        // dd('ok');

        $compliances = Compliance::all();
        $monthlyProjections = [];

        $currentMonth = Carbon::now()->startOfMonth();

        foreach ($compliances as $compliance) {

            $referenceDate = Carbon::parse($compliance->reference_date);
            $startWorkingOn = $compliance->start_on; // Numeric value from 'Start Working On' table

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

    public function projections()
    {
        // Get the current date and the start of the current month
        // $currentMonthStart = Carbon::now()->startOfMonth();

        // Fetch all compliances
        $compliances = Compliance::all();
        
        // Process compliance deadlines
        $complianceDeadlines = [];

        foreach ($compliances as $compliance) {
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
            $result[] = [
                'compliance_name' => $item['compliance']['compliance_name'],
                'deadline' => $item['deadline']
            ];
        }

        // Pass the results to the Blade view
        return view('components.projection', ['deadlines' => $result]);

    }

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

}