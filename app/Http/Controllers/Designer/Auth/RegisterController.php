<?php

namespace App\Http\Controllers\Designer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('designer.guest');
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('designer.auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:designers'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
        ]);

        $designer = Designer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'bio' => $request->bio,
            'portfolio_url' => $request->portfolio_url,
            'is_active' => true,
        ]);

        Auth::guard('designer')->login($designer);

        return redirect()->route('designer.dashboard');
    }
}
