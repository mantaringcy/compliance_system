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
        $compliances = Compliance::all();
        $complianceProjections = [];

        foreach ($compliances as $compliance) {
            $complianceDate = Carbon::parse($compliance->compliance_date);
            $startWorkingOn = $compliance->start_working_on; // Numeric value from 'Start Working On' table

            // Adjust the compliance date based on the numeric value
            switch ($startWorkingOn) {
                case 1:
                    $adjustedDate = $complianceDate->subWeeks(1); // 1 week before
                    break;
                case 2:
                    $adjustedDate = $complianceDate->subWeeks(2); // 2 weeks before
                    break;
                case 3:
                    $adjustedDate = $complianceDate->subMonth(1); // 1 month before
                    break;
                case 4:
                    $adjustedDate = $complianceDate->subMonths(2); // 2 months before
                    break;
                case 5:
                    $adjustedDate = $complianceDate->subMonths(3); // 3 months before
                    break;
                case 6:
                    $adjustedDate = $complianceDate->subMonths(4); // 4 months before
                    break;
                default:
                    $adjustedDate = $complianceDate; // No change if value is invalid
            }

            // Generate future projections from the adjusted date
            $monthlyProjection = [];
            for ($i = 0; $i < 12; $i++) {
                $nextMonth = $adjustedDate->copy()->addMonths($i)->startOfMonth();
                $monthlyProjection[] = $nextMonth->format('Y-m-d');
            }

            // Store the projections for each compliance
            $complianceProjections[] = [
                'compliance' => $compliance,
                'monthlyProjection' => $monthlyProjection
            ];
        }

        return view('compliance.index', [
            'complianceProjections' => $complianceProjections
        ]);
    }


}