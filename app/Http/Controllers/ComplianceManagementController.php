<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\MonthlyCompliance;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class ComplianceManagementController extends Controller
{
    private function token()
    {
        $client_id = \Config('static_data.google.client_id');
        $client_secret = \Config('static_data.google.client_secret');
        $refresh_token = \Config('static_data.google.refresh_token'); 
    
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'] ?? null;
        }
    
        // Log the error or throw an exception
        Log::error('Failed to refresh Google API token.', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    
        return null; // or throw an exception
    }

    public function index(Request $request)
    {
        // $monthlyCompliances = MonthlyCompliance::all();
        // $monthlyCompliances = MonthlyCompliance::where('status', 'completed')->get();

        // dd($monthlyCompliances);

        // Retrieve departments from the database
        $departments = Department::all()->toArray(); 

        // Fetch monthly compliances and map department name and days difference
        $monthlyCompliances = MonthlyCompliance::where('status', 'completed')->get()->map(function ($compliance) use ($departments) {
            // Calculate days difference
            $compliance->days_difference = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($compliance->computed_deadline)->startOfDay(), false);
            
            // Get department name using the method
            $compliance->department_name = $this->getDepartmentName($compliance->department_id, $departments);
            
            return $compliance;
        });

        // Sort the compliances:
        // - First by 'status' (completed at the bottom)
        // - Then by 'deadline' in ascending order
        $monthlyCompliances = $monthlyCompliances->sortBy(function($compliance) {
            // First, sort by 'status', completed should come last
            return $compliance['status'] == 'completed' ? 1 : 0;
        })->sortBy(function($compliance) {
            // Then, sort by 'deadline' (ascending)
            return Carbon::parse($compliance['deadline']);
        });

        if ($request->ajax()) {
            return DataTables::of($monthlyCompliances)
                ->addColumn('compliance_id', function ($monthlyCompliance) {
                    return $monthlyCompliance->compliance_id;
                })
                ->addColumn('compliance_name', function ($monthlyCompliance) {
                    return $monthlyCompliance->compliance_name;
                })
                ->addColumn('department_id', function ($monthlyCompliance) {
                    return $monthlyCompliance->department_name;
                })
                ->addColumn('deadline', function ($monthlyCompliance) {
                    $deadline = \Carbon\Carbon::parse($monthlyCompliance['computed_deadline'])->format('F j, Y');
                    return $deadline;
                })
                ->addColumn('status', function ($monthlyCompliance) {
                    $status = $monthlyCompliance->status;

                    switch($status) {
                        case 'completed':
                            return '<span class="badge badge-green">COMPLETED</span>';
                            break;
                        case 'in_progress':
                            return '<span class="badge badge-blue-light">IN PROGRESS</span>';
                            break;
                        case 'pending':
                            return '<span class="badge badge-yellow-light">PENDING</span>';
                            break;
                        default: '<span class="badge badge-yellow-light">PENDING</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function ($monthlyCompliance) {
                    return '<a href="' . route('compliance-management.edit', $monthlyCompliance->id) . '" class="edit-btn edit-compliance"><i class="fa-regular fa-pen-to-square"></i></a>';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('components.logs', compact('monthlyCompliances'));
    }

    public function edit($id)
    {
        $monthlyCompliance = MonthlyCompliance::findOrFail($id);

        $monthlyComplianceMessage = MonthlyCompliance::with('messages.user') // Load messages with user info
        ->findOrFail($id);

        // Retrieve departments from the database

        $departments = Department::pluck('department_name', 'id'); // Returns an associative array: [id => name]


        
        // Fetch ENUM values for the `status` column
        $enumValues = $this->getEnumValues('monthly_compliances', 'status');

        return view('forms.compliance-management-update', compact('monthlyCompliance', 'enumValues', 'monthlyComplianceMessage', 'departments'));
    }

    private function getEnumValues($table, $column)
    {
        $type = DB::selectOne("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column]);

        if ($type && preg_match('/^enum\((.*)\)$/', $type->Type, $matches)) {
            return array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }
    
        return []; // Return an empty array if no ENUM values are found
    }

    public function update(Request $request, $id)
    {
        $monthlyCompliance = MonthlyCompliance::findOrFail($id);

        $fields = $request->validate([
            'status' => 'required|string',  // Ensure the status is provided
            'approve' => 'required|boolean', // Ensure approve is provided
            'images.*' => 'nullable|mimes:jpeg,jpg,png,pdf|max:51200',  // Validates images (jpeg, jpg, png, pdf) and max size (10MB)
        ],
        [
            'images.*.mimes' => 'Only JPEG, PNG, and PDF files are allowed.',
            'images.*.max' => 'Each file must not exceed 10MB.',
        ]);

        $monthlyCompliance->update([
            'status' => $fields['status'],
            'approve' => $fields['approve'],
            'approved_at' => now(), // Set the current timestamp when approving
        ]);

        // if ($request->hasFile('images')) {
        //     $request->validate([
        //         'images' => 'required|array', // Ensure multiple files are uploaded
        //         'images.*' => 'mimes:png,jpg,jpeg,pdf|max:2048', // Validate file types and size
        //     ]);
            
        //     // Get existing file paths from the database (if any)
        //     $existingPaths = json_decode($monthlyCompliance->file_path, true) ?? [];
    
        //     // Initialize an array to store paths for newly uploaded images
        //     $newPaths = [];
    
        //     // Process the uploaded images
        //     foreach ($request->file('images') as $file) {
        //         // Generate a unique file name and store the image
        //         $newPaths[] = $file->store('monthly_compliance_images', 'public');
        //     }
    
        //     // Combine existing paths with the newly uploaded image paths
        //     $allPaths = array_merge($existingPaths, $newPaths);
    
        //     // Update the file_path column in the database
        //     $monthlyCompliance->update([
        //         'file_path' => json_encode($allPaths),  // Save combined array as JSON
        //     ]);
        // }

       

        return redirect()->back()->with('success', 'Compliance updated successfully.');

        // return view('forms.compliance-management-update');
    }

    public function uploadImage(Request $request, $id)
    {
        $monthlyCompliance = MonthlyCompliance::findOrFail($id);

        $fields = $request->validate([
            'images.*' => 'nullable|mimes:jpeg,jpg,png,pdf|max:51200',  // Validates images (jpeg, jpg, png, pdf) and max size (10MB)
        ],
        [
            'images.*.mimes' => 'Only JPEG, PNG, and PDF files are allowed.',
            'images.*.max' => 'Each file must not exceed 10MB.',
        ]);

        // Get the department based on department_id
        $department = Department::find($monthlyCompliance->department_id);
        if (!$department) {
            return response('Department not found.', 404);
        }

        // Create the folder structure
        $departmentName = $department->department_name; // Get the department name
        $complianceFolderName = $monthlyCompliance->compliance_id . ' - ' . $monthlyCompliance->compliance_name; // "ID - Compliance Name"
        $computedDeadline = Carbon::parse($monthlyCompliance->computed_deadline)->format('Y-m-d'); // Format the computed deadline

        // Define the base path for storage
        $basePath = 'monthly_compliance_images/' . $departmentName . '/' . $complianceFolderName;

        // Create the directory if it doesn't exist
        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath, 0755, true); // Create the directory with permissions
        }

        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'required|array', // Ensure multiple files are uploaded
                'images.*' => 'mimes:png,jpg,jpeg,pdf|max:2048', // Validate file types and size
            ]);
            
            // Get existing file paths from the database (if any)
            $existingPaths = json_decode($monthlyCompliance->file_path, true) ?? [];
    
            // Initialize an array to store paths for newly uploaded images
            $newPaths = [];
    
            // Process the uploaded images
            foreach ($request->file('images') as $file) {
                // Generate a unique file name and store the image
                $newPaths[] = $file->store($basePath, 'public');

                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    // Use the original name of the file
                    // $fileName = $file->getClientOriginalName();

                    $fileName = Carbon::parse($monthlyCompliance->computed_deadline)->format('Y-m-d') . ' - ' . $file->getClientOriginalName();
    
                    // Upload the file to Google Drive (or wherever you want)
                    // Gdrive::put($departmentName . $complianceFolderName . '/' . $fileName , $file);
                }
            }
    
            // Combine existing paths with the newly uploaded image paths
            $allPaths = array_merge($existingPaths, $newPaths);
    
            // Update the file_path column in the database
            $monthlyCompliance->update([
                'file_path' => json_encode($allPaths),  // Save combined array as JSON
            ]);
        }

        $uploadedPaths = json_decode($monthlyCompliance->file_path, true) ?? [];

        $monthlyCompliance->update([
            'status' => 'in_progress',
        ]);

       return response()->json([
            'success' => true,
            'filePaths' => $uploadedPaths,
        ]);
       
    }

    public function deleteImage(Request $request, $id)
    {
        $monthlyCompliance = MonthlyCompliance::findOrFail($id);

        // Get the filePath from the request
        $filePath = $request->input('file_path');
    
        // if (!$filePath) {
        //     return response()->json(['error' => 'File path is required.'], 400);
        // }
    
        // Decode the file_path JSON from the database
        $filePaths = json_decode($monthlyCompliance->file_path, true);
    
        if (!empty($filePaths) && in_array($filePath, $filePaths)) {
            // Remove the specific file from the array
            $filePaths = array_filter($filePaths, function ($path) use ($filePath) {
                return $path !== $filePath;
            });
    
            // Delete the specific file from storage
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
    
            // Update the database with the remaining file paths
            $monthlyCompliance->update([
                'file_path' => json_encode(array_values($filePaths)), // Reindex the array and save it
            ]);
    
            // return response()->json(['message' => 'Image deleted successfully.'], 200);
        }

        $uploadedPaths = json_decode($monthlyCompliance->file_path, true) ?? [];

        // return response()->json([
        //     'success' => true,
        //     'filePaths' => $uploadedPaths,
        // ]);
    
        return redirect()->back()->with('success', 'Compliance updated successfully.');
        // return 
        // return response()->json(['error' => 'Image not found.'], 404);
        
    }

    // Change Numeric to DepartmentName
    public function getDepartmentName($departmentId, $departments) {
        return $departments[$departmentId - 1]['department_name'] ?? 'Unknown Department';
    }

    public function updateStatus($id, Request $request)
    {
        $compliance = MonthlyCompliance::findOrFail($id); // Find the compliance record by ID
        
        // Update the status
        $compliance->status = $request->status;
        $compliance->save();

        return response()->json(['success' => true]);
    }

    public function approve($id, Request $request)
    {
        // Find the compliance record by ID
        $compliance = MonthlyCompliance::findOrFail($id);

        // Update the status to "approved" (1)
        $compliance->approve = $request->status;
        $compliance->status = 'completed';
        $compliance->save();

        // Get the department name based on department_id
        $department = Department::find($compliance->department_id);
        if (!$department) {
            return response('Department not found.', 404);
        }

        // Create the folder name based on department name and compliance details
        $departmentName = $department->department_name; // Assuming the department table has a 'name' column
        $complianceFolderName = $compliance->compliance_id . ' - ' . $compliance->compliance_name; // "ID - Compliance Name"
        $imageFolderName = Carbon::parse($compliance->computed_deadline)->format('Y-m-d') . ' - ' . $compliance->compliance_name; // "YYYY-MM-DD - Compliance Name"

        // Get the file paths from the database
        $filePaths = json_decode($compliance->file_path, true) ?? [];
        
        // Initialize an array to store the results of the uploads
        $uploadResults = [];

        // Loop through each file path and upload to Google Drive
        foreach ($filePaths as $index => $filePath) {
            // Get the local file path
            $localFilePath = storage_path('app/public/' . $filePath); // Adjust the path as necessary

            // Check if the file exists
            if (file_exists($localFilePath)) {
                // Construct the file name
                $fileName = $imageFolderName; // Base name
                if (count($filePaths) > 1) {
                    $fileName .= ' (' . ($index + 1) . ')'; // Append (1), (2), etc. for multiple files
                }
                $fileName .= '.' . pathinfo($filePath, PATHINFO_EXTENSION); // Add the file extension

                // Upload the file to Google Drive
                try {
                    // Specify the destination path in Google Drive
                    $drivePath = $departmentName . ' / ' . $complianceFolderName . '/' . $fileName;
                    $file = new \Illuminate\Http\File($localFilePath);

                    Gdrive::put($drivePath, $file);

                    // Store the result of the upload
                    $uploadResults[] = [
                        'file' => $filePath,
                        'status' => 'uploaded',
                        'drivePath' => $drivePath,
                    ];
                } catch (\Exception $e) {
                    // Handle any errors during the upload
                    $uploadResults[] = [
                        'file' => $filePath,
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ];
                }
            } else {
                // File does not exist
                $uploadResults[] = [
                    'file' => $filePath,
                    'status' => 'not found',
                ];
            }
        }

        // Return a JSON response indicating success and upload results
        return response()->json([
            'success' => true,
            'uploadResults' => $uploadResults,
        ]);
    }
}
