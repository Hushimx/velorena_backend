<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Marketer;
use App\Models\Category;
use App\Imports\LeadsImport;
use App\Exports\LeadsTemplateExport;
use App\Services\LeadsImportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.leads.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marketers = Marketer::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.dashboard.leads.create', compact('marketers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,closed_won,closed_lost',
            'priority' => 'required|in:low,medium,high',
            'marketer_id' => 'nullable|exists:marketers,id',
            'category_id' => 'nullable|exists:categories,id',
            'next_follow_up' => 'nullable|date',
        ]);

        Lead::create($request->all());

        return redirect()->route('admin.leads.index')
            ->with('success', __('admin.lead_created_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $lead->load(['marketer', 'communications.marketer']);
        return view('admin.dashboard.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $marketers = Marketer::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.dashboard.leads.edit', compact('lead', 'marketers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,closed_won,closed_lost',
            'priority' => 'required|in:low,medium,high',
            'marketer_id' => 'nullable|exists:marketers,id',
            'category_id' => 'nullable|exists:categories,id',
            'next_follow_up' => 'nullable|date',
        ]);

        $lead->update($request->all());

        return redirect()->route('admin.leads.index')
            ->with('success', __('admin.lead_updated_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', __('admin.lead_deleted_success'));
    }

    /**
     * Download Excel template for leads import
     */
    public function downloadTemplate()
    {
        return Excel::download(new LeadsTemplateExport, 'leads_template.xlsx');
    }

    /**
     * Show bulk upload form
     */
    public function bulkUpload()
    {
        return view('admin.dashboard.leads.bulk-upload');
    }

    /**
     * Process bulk upload of leads
     */
    public function processBulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            // Get the uploaded file
            $file = $request->file('file');
            
            // Check if file exists and has a valid path
            if (!$file || !$file->isValid()) {
                throw new \Exception('Invalid file uploaded');
            }
            
            // Log the file info for debugging
            Log::info('Uploading file: ' . $file->getClientOriginalName());
            Log::info('File path: ' . $file->getPathname());
            Log::info('File size: ' . $file->getSize());
            
            // Use our custom import service directly to avoid Laravel Excel issues
            $importService = new LeadsImportService();
            $extension = strtolower($file->getClientOriginalExtension());
            
            if ($extension === 'csv') {
                $results = $importService->importFromCsv($file);
            } else {
                try {
                    $results = $importService->importFromExcel($file);
                } catch (\Exception $excelError) {
                    // If Excel processing fails, suggest CSV conversion
                    throw new \Exception('Excel file processing failed. Please convert your Excel file to CSV format and try again. Error: ' . $excelError->getMessage());
                }
            }
            
            // Prepare success message
            if (is_array($results)) {
                $message = "Import completed: {$results['success']} leads imported successfully";
                if ($results['skipped'] > 0) {
                    $message .= ", {$results['skipped']} leads skipped";
                }
                if ($results['errors'] > 0) {
                    $message .= ", {$results['errors']} errors encountered";
                }
            } else {
                $message = $results;
            }
            
            return redirect()->route('admin.leads.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Bulk upload error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', __('admin.leads_upload_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }
}
