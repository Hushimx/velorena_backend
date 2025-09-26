<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GuestCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the user login form.
     */
    public function showLoginForm()
    {
        // Additional check to ensure authenticated users are redirected
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login'); // Regular user login view
    }

    /**
     * Handle user login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            // Merge guest cart with user cart if guest cart exists
            $guestCartService = app(GuestCartService::class);
            $guestCartSummary = $guestCartService->getCartSummary();
            
            if ($guestCartSummary['item_count'] > 0) {
                $mergedCount = $guestCartService->mergeWithUserCart(Auth::id());
                
                if ($mergedCount > 0) {
                    session()->flash('cart_merged', "تم دمج {$mergedCount} منتج من سلة الضيف مع حسابك");
                }
            }

            // Redirect to intended URL or home page after successful login
            $intendedUrl = session('url.intended', '/');
            session()->forget('url.intended');
            
            return redirect($intendedUrl)->with('status', __('Logged in successfully'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log out the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
