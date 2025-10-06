<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsController extends Controller
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
        $posts = Post::with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.dashboard.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dashboard.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_ar' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'excerpt_ar' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:60',
            'meta_title_ar' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_description_ar' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'meta_keywords_ar' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_title_ar' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:300',
            'og_description_ar' => 'nullable|string|max:300',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'canonical_url' => 'nullable|url',
            'robots' => 'nullable|string',
            'structured_data' => 'nullable|json',
        ]);

        $data = $request->all();

        // Generate slug from title
        $data['slug'] = $this->generateUniqueSlug($request->title);

        // Set admin_id
        $data['admin_id'] = Auth::guard('admin')->id();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts/featured', 'public');
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('posts/og', 'public');
        }

        // Set published_at if status is published and no date provided
        if ($request->status === 'published' && empty($request->published_at)) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        // If this is a preview request, redirect to preview page
        if ($request->has('preview')) {
            return redirect()->route('admin.posts.preview', $post);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', trans('posts.post_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('admin');
        return view('admin.dashboard.posts.show', compact('post'));
    }

    /**
     * Preview the post
     */
    public function preview(Post $post)
    {
        try {
            // Load the admin relationship
            $post->load('admin');

            // Debug logging if in debug mode
            if (config('app.debug')) {
                \Log::info('Preview Post Debug', [
                    'post_id' => $post->id,
                    'post_title' => $post->title,
                    'post_status' => $post->status,
                    'has_content' => !empty($post->content),
                    'has_admin' => $post->admin ? true : false,
                    'admin_name' => $post->admin->name ?? 'No admin',
                ]);
            }

            return view('admin.dashboard.posts.preview', compact('post'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Preview Post Error: ' . $e->getMessage());

            // Return a simple error view
            return view('admin.dashboard.posts.preview', [
                'post' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('admin.dashboard.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_ar' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'excerpt_ar' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:60',
            'meta_title_ar' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_description_ar' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'meta_keywords_ar' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_title_ar' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:300',
            'og_description_ar' => 'nullable|string|max:300',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'canonical_url' => 'nullable|url',
            'robots' => 'nullable|string',
            'structured_data' => 'nullable|json',
        ]);

        $data = $request->all();

        // Generate new slug if title changed
        if ($request->title !== $post->title) {
            $data['slug'] = $this->generateUniqueSlug($request->title, $post->id);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('posts/featured', 'public');
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($post->og_image) {
                Storage::disk('public')->delete($post->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('posts/og', 'public');
        }

        // Set published_at if status is published and no date provided
        if ($request->status === 'published' && empty($request->published_at) && !$post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')
            ->with('success', trans('posts.post_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete images
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        if ($post->og_image) {
            Storage::disk('public')->delete($post->og_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', trans('posts.post_deleted_successfully'));
    }

    /**
     * Generate a unique slug for the post
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Post::where('slug', $slug)->when($excludeId, function ($query) use ($excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
