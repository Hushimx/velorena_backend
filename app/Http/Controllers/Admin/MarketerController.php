<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MarketerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.marketers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.dashboard.marketers.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:marketers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        Marketer::create($data);

        return redirect()->route('admin.marketers.index')
            ->with('success', 'تم إنشاء المسوق بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marketer $marketer)
    {
        return view('admin.dashboard.marketers.show', compact('marketer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marketer $marketer)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.dashboard.marketers.edit', compact('marketer', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marketer $marketer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:marketers,email,' . $marketer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $marketer->update($data);

        return redirect()->route('admin.marketers.index')
            ->with('success', 'تم تحديث المسوق بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marketer $marketer)
    {
        // Check if marketer has leads
        if ($marketer->leads()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المسوق لأنه لديه leads مسندة إليه');
        }

        $marketer->delete();

        return redirect()->route('admin.marketers.index')
            ->with('success', 'تم حذف المسوق بنجاح');
    }
}
