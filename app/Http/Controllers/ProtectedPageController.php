<?php

namespace App\Http\Controllers;

use App\Models\ProtectedPage;
use Illuminate\Http\Request;

class ProtectedPageController extends Controller
{
    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        $page = ProtectedPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Check if user can access this page
        if (!$page->canAccess(auth()->user())) {
            abort(403, 'Access denied');
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Display a page by type (for specific page types like about, terms, etc.)
     */
    public function showByType($type)
    {
        $page = ProtectedPage::where('type', $type)
            ->where('access_level', 'public')
            ->where('is_active', true)
            ->first();

        if (!$page) {
            abort(404, 'Page not found');
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Display about us page
     */
    public function about()
    {
        return $this->showByType('about');
    }

    /**
     * Display terms of service page
     */
    public function terms()
    {
        return $this->showByType('terms');
    }

    /**
     * Display privacy policy page
     */
    public function privacy()
    {
        return $this->showByType('privacy');
    }
}
