<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProtectedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.pages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dashboard.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_ar' => 'nullable|string',
            'type' => 'required|in:page,section,modal',
            'access_level' => 'required|in:public,authenticated,admin',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_description_ar' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'meta_keywords_ar' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug if not provided
        $validated['slug'] = Str::slug($validated['title']);

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('pages/og-images', 'public');
        }

        // Handle page images upload
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('pages/images', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        ProtectedPage::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProtectedPage $page)
    {
        return view('admin.dashboard.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProtectedPage $page)
    {
        return view('admin.dashboard.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProtectedPage $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_ar' => 'nullable|string',
            'type' => 'required|in:page,section,modal',
            'access_level' => 'required|in:public,authenticated,admin',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_description_ar' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'meta_keywords_ar' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update slug if title changed
        if ($page->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('pages/og-images', 'public');
        }

        // Handle page images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($page->images) {
                foreach ($page->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('pages/images', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProtectedPage $page)
    {
        // Delete associated images
        if ($page->og_image) {
            Storage::disk('public')->delete($page->og_image);
        }
        if ($page->images) {
            foreach ($page->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
