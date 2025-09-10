<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminResourceController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->paginate(20);
        return view('admin.dashboard.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.dashboard.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', __('admin.admin_created_success'));
    }

    public function show(Admin $admin)
    {
        return view('admin.dashboard.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.dashboard.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', __('admin.admin_updated_success'));
    }

    public function destroy(Admin $admin)
    {
        // Prevent deleting the current admin
        if ($admin->id === auth()->guard('admin')->id()) {
            return redirect()->back()
                ->with('error', __('admin.cannot_delete_self'));
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', __('admin.admin_deleted_success'));
    }
}
