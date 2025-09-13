<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::ordered()->get();
        return view('admin.dashboard.home-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.dashboard.home-banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:1|max:10'
        ]);

        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/home-banners');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/home-banners/' . $imageName;
            }

            HomeBanner::create($data);

            return redirect()->route('admin.home-banners.index')
                ->with('success', trans('admin.banner_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating banner: ' . $e->getMessage());
        }
    }

    public function show(HomeBanner $banner)
    {
        return view('admin.dashboard.home-banners.show', compact('banner'));
    }

    public function edit(HomeBanner $banner)
    {
        return view('admin.dashboard.home-banners.edit', compact('banner'));
    }

    public function update(Request $request, HomeBanner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:1|max:10'
        ]);

        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($banner->image && file_exists(public_path($banner->image))) {
                    unlink(public_path($banner->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/home-banners');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/home-banners/' . $imageName;
            }

            $banner->update($data);

            return redirect()->route('admin.home-banners.index')
                ->with('success', trans('admin.banner_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating banner: ' . $e->getMessage());
        }
    }

    public function destroy(HomeBanner $banner)
    {
        try {
            // Delete image if exists
            if ($banner->image && file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }

            $banner->delete();

            return redirect()->route('admin.home-banners.index')
                ->with('success', trans('admin.banner_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting banner: ' . $e->getMessage());
        }
    }
}