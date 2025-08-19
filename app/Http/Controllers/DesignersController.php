<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class DesignersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard.designers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dashboard.designers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:designers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'portfolio_url' => 'nullable|url|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            Designer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'bio' => $request->bio,
                'portfolio_url' => $request->portfolio_url,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.designers.index')
                ->with('success', trans('designers.designer_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the designer.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $designer = Designer::findOrFail($id);
        return view('admin.dashboard.designers.show', compact('designer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $designer = Designer::findOrFail($id);
        return view('admin.dashboard.designers.edit', compact('designer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $designer = Designer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:designers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'portfolio_url' => 'nullable|url|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'bio' => $request->bio,
                'portfolio_url' => $request->portfolio_url,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $designer->update($data);

            return redirect()->route('admin.designers.index')
                ->with('success', trans('designers.designer_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the designer.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $designer = Designer::findOrFail($id);
            $designer->delete();

            return redirect()->route('admin.designers.index')
                ->with('success', trans('designers.designer_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', trans('designers.error_deleting_designer'));
        }
    }
}
