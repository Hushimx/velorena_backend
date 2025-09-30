<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.categories.index');
    }

    public function create()
    {
        return view('admin.dashboard.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Exclude file fields from data array - we'll add processed paths
        $data = $request->except(['slider_image', 'main_image']);


        // Handle slider image upload
        if ($request->hasFile('slider_image')) {
            $sliderImage = $request->file('slider_image');
            $sliderImageName = time() . '_slider_' . $sliderImage->getClientOriginalName();
            $path = $sliderImage->storeAs('categories', $sliderImageName, 'public');
            $data['slider_image'] = Storage::url($path);
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImageName = time() . '_main_' . $mainImage->getClientOriginalName();
            $path = $mainImage->storeAs('categories', $mainImageName, 'public');
            $data['main_image'] = Storage::url($path);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', trans('categories.category_created_successfully'));
    }

    public function show(Category $category)
    {
        return view('admin.dashboard.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.dashboard.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Exclude file fields from data array - we'll add processed paths
        $data = $request->except(['slider_image', 'main_image']);


        // Handle slider image upload
        if ($request->hasFile('slider_image')) {
            // Delete old slider image if exists
            if ($category->slider_image) {
                $oldPath = str_replace('/storage/', '', $category->slider_image);
                Storage::disk('public')->delete($oldPath);
            }

            $sliderImage = $request->file('slider_image');
            $sliderImageName = time() . '_slider_' . $sliderImage->getClientOriginalName();
            $path = $sliderImage->storeAs('categories', $sliderImageName, 'public');
            $data['slider_image'] = Storage::url($path);
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            // Delete old main image if exists
            if ($category->main_image) {
                $oldPath = str_replace('/storage/', '', $category->main_image);
                Storage::disk('public')->delete($oldPath);
            }

            $mainImage = $request->file('main_image');
            $mainImageName = time() . '_main_' . $mainImage->getClientOriginalName();
            $path = $mainImage->storeAs('categories', $mainImageName, 'public');
            $data['main_image'] = Storage::url($path);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', trans('categories.category_updated_successfully'));
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', trans('categories.cannot_delete_with_products'));
        }


        // Delete slider image if exists
        if ($category->slider_image) {
            $oldPath = str_replace('/storage/', '', $category->slider_image);
            Storage::disk('public')->delete($oldPath);
        }

        // Delete main image if exists
        if ($category->main_image) {
            $oldPath = str_replace('/storage/', '', $category->main_image);
            Storage::disk('public')->delete($oldPath);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', trans('categories.category_deleted_successfully'));
    }
}
