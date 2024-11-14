<?php

namespace App\Http\Controllers;

use App\Models\MonthlyCompliance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ComplianceManagementController extends Controller
{
    public function index(Request $request)
    {
        $monthlyCompliances = MonthlyCompliance::all();

        if ($request->ajax()) {
            return DataTables::of($monthlyCompliances)
                ->addColumn('compliance_id', function ($monthlyCompliance) {
                    return $monthlyCompliance->compliance_id;
                })
                ->addColumn('compliance_name', function ($monthlyCompliance) {
                    return $monthlyCompliance->compliance_name;
                })
                ->addColumn('department_id', function ($monthlyCompliance) {
                    return $monthlyCompliance->department_id;
                })
                ->addColumn('deadline', function ($monthlyCompliance) {
                    return $monthlyCompliance->computed_deadline;
                })
                ->addColumn('status', function ($monthlyCompliance) {
                    return $monthlyCompliance->status;
                })
                ->addColumn('action', function ($monthlyCompliance) {
                    // return 'Action';
                    return '<a href="' . route('compliance-management.edit', $monthlyCompliance->id) . '" class="">Edit</a>';
                })
                ->make(true);
        }

        return view('module.compliance-management', compact('monthlyCompliances'));
    }

    public function edit($id)
    {
        $monthlyCompliance = MonthlyCompliance::findOrFail($id);
        
        // Fetch ENUM values for the `status` column
        $enumValues = $this->getEnumValues('monthly_compliances', 'status');

        return view('forms.compliance-management-update', compact('monthlyCompliance', 'enumValues'));
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

        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'required|array', // Ensure multiple files are uploaded
                'images.*' => 'mimes:png,jpg,jpeg,pdf|max:2048', // Validate file types and size
            ]);
            
            // Find the compliance record
            // $monthlyCompliance = MonthlyCompliance::findOrFail($id);
    
            // Get existing file paths from the database (if any)
            $existingPaths = json_decode($monthlyCompliance->file_path, true) ?? [];
    
            // Initialize an array to store paths for newly uploaded images
            $newPaths = [];
    
            // Process the uploaded images
            foreach ($request->file('images') as $file) {
                // Generate a unique file name and store the image
                $newPaths[] = $file->store('monthly_compliance_images', 'public');
            }
    
            // Combine existing paths with the newly uploaded image paths
            $allPaths = array_merge($existingPaths, $newPaths);
    
            // Update the file_path column in the database
            $monthlyCompliance->update([
                'file_path' => json_encode($allPaths),  // Save combined array as JSON
            ]);
        }

       

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

        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'required|array', // Ensure multiple files are uploaded
                'images.*' => 'mimes:png,jpg,jpeg,pdf|max:2048', // Validate file types and size
            ]);
            
            // Find the compliance record
            // $monthlyCompliance = MonthlyCompliance::findOrFail($id);
    
            // Get existing file paths from the database (if any)
            $existingPaths = json_decode($monthlyCompliance->file_path, true) ?? [];
    
            // Initialize an array to store paths for newly uploaded images
            $newPaths = [];
    
            // Process the uploaded images
            foreach ($request->file('images') as $file) {
                // Generate a unique file name and store the image
                $newPaths[] = $file->store('monthly_compliance_images', 'public');
            }
    
            // Combine existing paths with the newly uploaded image paths
            $allPaths = array_merge($existingPaths, $newPaths);
    
            // Update the file_path column in the database
            $monthlyCompliance->update([
                'file_path' => json_encode($allPaths),  // Save combined array as JSON
            ]);
        }

        $uploadedPaths = json_decode($monthlyCompliance->file_path, true) ?? [];

       return response()->json([
            'success' => true,
            'filePaths' => $uploadedPaths,
        ]);
       

        // return redirect()->back()->with('success', 'Image uploaded successfully.');
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
}
