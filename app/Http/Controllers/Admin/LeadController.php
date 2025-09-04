<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Marketer;
use Illuminate\Http\Request;

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
        return view('admin.dashboard.leads.create', compact('marketers'));
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
        return view('admin.dashboard.leads.edit', compact('lead', 'marketers'));
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
}
