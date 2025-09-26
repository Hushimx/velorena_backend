<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = StoreContent::orderBy('sort_order')->get()->groupBy('type');
        return view('admin.dashboard.site-settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dashboard.site-settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:store_contents,key',
            'type' => 'required|in:setting,content,media',
            'value_en' => 'nullable|array',
            'value_ar' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('site-settings', 'public');
            $validated['value_en']['logo'] = $logoPath;
            $validated['value_ar']['logo'] = $logoPath;
        }

        StoreContent::create($validated);

        return redirect()->route('admin.site-settings.index')
            ->with('success', 'Site setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StoreContent $siteSetting)
    {
        return view('admin.dashboard.site-settings.show', compact('siteSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreContent $siteSetting)
    {
        return view('admin.dashboard.site-settings.edit', compact('siteSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StoreContent $siteSetting)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:store_contents,key,' . $siteSetting->id,
            'type' => 'required|in:setting,content,media',
            'value_en' => 'nullable|array',
            'value_ar' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            $oldLogo = $siteSetting->value_en['logo'] ?? null;
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $logoPath = $request->file('logo')->store('site-settings', 'public');
            $validated['value_en']['logo'] = $logoPath;
            $validated['value_ar']['logo'] = $logoPath;
        }

        $siteSetting->update($validated);

        return redirect()->route('admin.site-settings.index')
            ->with('success', 'Site setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreContent $siteSetting)
    {
        // Delete associated files
        if ($siteSetting->value_en && isset($siteSetting->value_en['logo'])) {
            Storage::disk('public')->delete($siteSetting->value_en['logo']);
        }

        $siteSetting->delete();

        return redirect()->route('admin.site-settings.index')
            ->with('success', 'Site setting deleted successfully.');
    }

    /**
     * Update multiple settings at once
     */
    public function updateBulk(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            $setting = StoreContent::where('key', $key)->first();
            
            if ($setting) {
                $currentValue = $setting->value_en ?? [];
                $currentValueAr = $setting->value_ar ?? [];
                
                // Update values based on current locale
                if (app()->getLocale() === 'ar') {
                    $currentValueAr = array_merge($currentValueAr, $value);
                    $setting->update(['value_ar' => $currentValueAr]);
                } else {
                    $currentValue = array_merge($currentValue, $value);
                    $setting->update(['value_en' => $currentValue]);
                }
            }
        }

        return redirect()->route('admin.site-settings.index')
            ->with('success', 'Site settings updated successfully.');
    }
}
