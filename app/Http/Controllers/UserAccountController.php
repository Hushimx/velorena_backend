<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the delete account page
     */
    public function showDeleteAccount()
    {
        return view('users.delete-account');
    }

    /**
     * Process the account deletion
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_delete' => 'required|accepted',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => __('The password is incorrect.')]);
        }

        // Log the user out
        Auth::logout();

        // Delete the user account
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', __('Your account has been successfully deleted.'));
    }
}

