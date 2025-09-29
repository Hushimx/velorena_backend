<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for locale in URL parameter first
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['en', 'ar'])) {
                session(['locale' => $locale]);
                app()->setLocale($locale);
            }
        }
        // Check if locale is set in session
        elseif (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // Default to Arabic
            app()->setLocale('ar');
        }

        return $next($request);
    }
}

