<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AvailabilitySlotController extends Controller
{
    public function index()
    {
        $availabilitySlots = AvailabilitySlot::orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(20);
        
        return view('admin.dashboard.availability-slots.index', compact('availabilitySlots'));
    }

    public function create()
    {
        return view('admin.dashboard.availability-slots.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration_minutes' => 'required|integer|min:5|max:120',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for overlapping slots
        $overlapping = AvailabilitySlot::where('day_of_week', $request->day_of_week)
            ->where('is_active', true)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('admin.availability_slot_overlap_error'));
        }

        AvailabilitySlot::create([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'slot_duration_minutes' => $request->slot_duration_minutes,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.availability-slots.index')
            ->with('success', __('admin.availability_slot_created_success'));
    }

    public function show(AvailabilitySlot $availabilitySlot)
    {
        return view('admin.dashboard.availability-slots.show', compact('availabilitySlot'));
    }

    public function edit(AvailabilitySlot $availabilitySlot)
    {
        return view('admin.dashboard.availability-slots.edit', compact('availabilitySlot'));
    }

    public function update(Request $request, AvailabilitySlot $availabilitySlot)
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration_minutes' => 'required|integer|min:5|max:120',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for overlapping slots (excluding current slot)
        $overlapping = AvailabilitySlot::where('day_of_week', $request->day_of_week)
            ->where('id', '!=', $availabilitySlot->id)
            ->where('is_active', true)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('admin.availability_slot_overlap_error'));
        }

        $availabilitySlot->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'slot_duration_minutes' => $request->slot_duration_minutes,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.availability-slots.index')
            ->with('success', __('admin.availability_slot_updated_success'));
    }

    public function destroy(AvailabilitySlot $availabilitySlot)
    {
        $availabilitySlot->delete();

        return redirect()->route('admin.availability-slots.index')
            ->with('success', __('admin.availability_slot_deleted_success'));
    }

    public function toggleStatus(AvailabilitySlot $availabilitySlot)
    {
        $availabilitySlot->update([
            'is_active' => !$availabilitySlot->is_active
        ]);

        $status = $availabilitySlot->is_active ? __('admin.activated') : __('admin.deactivated');
        
        return redirect()->back()
            ->with('success', __('admin.availability_slot_status_updated', ['status' => $status]));
    }
}
