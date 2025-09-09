<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Marketer;
use App\Models\Category;
use App\Imports\LeadsImport;
use App\Exports\LeadsTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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
            ->with('success', 'تم إنشاء الـ lead بنجاح');
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
            ->with('success', 'تم تحديث الـ lead بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'تم حذف الـ lead بنجاح');
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
            Excel::import(new LeadsImport, $request->file('file'));
            
            return redirect()->route('admin.leads.index')
                ->with('success', 'تم رفع الـ leads بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage())
                ->withInput();
        }
    }
}
