<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
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
        return view('admin.dashboard.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.dashboard.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'description_ar' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'specifications' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'primary_image' => 'nullable|integer|min:0',
            // SEO Fields
            'meta_title' => 'nullable|string|max:60',
            'meta_title_ar' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_description_ar' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'og_title' => 'nullable|string|max:60',
            'og_title_ar' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:300',
            'og_description_ar' => 'nullable|string|max:300',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_title_ar' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:200',
            'twitter_description_ar' => 'nullable|string|max:200',
            'twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:50',
            'structured_data' => 'nullable|string'
        ]);

        try {
            $data = $request->all();

            // Set is_active to true by default
            $data['is_active'] = true;

            // Handle specifications as JSON
            if ($request->filled('specifications')) {
                $specifications = json_decode($request->specifications, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['specifications'] = $specifications;
                } else {
                    $data['specifications'] = null;
                }
            }

            // Handle structured data as JSON
            if ($request->filled('structured_data')) {
                $structuredData = json_decode($request->structured_data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['structured_data'] = $structuredData;
                } else {
                    $data['structured_data'] = null;
                }
            }

            // Handle SEO image uploads
            if ($request->hasFile('og_image')) {
                $ogImagePath = $request->file('og_image')->store('products/seo', 'public');
                $data['og_image'] = 'storage/' . $ogImagePath;
            }

            if ($request->hasFile('twitter_image')) {
                $twitterImagePath = $request->file('twitter_image')->store('products/seo', 'public');
                $data['twitter_image'] = 'storage/' . $twitterImagePath;
            }

            // Set default robots if not provided
            if (empty($data['robots'])) {
                $data['robots'] = 'index,follow';
            }

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Product::generateSlug($data['name']);
            }

            // Remove image-related fields from data
            unset($data['images'], $data['primary_image']);

            $product = Product::create($data);

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($product, $request->file('images'), $request->input('primary_image', 0));
            }

            return redirect()->route('admin.products.index')
                ->with('success', trans('products.product_created_successfully'));
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', trans('products.error_creating_product') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'options.values', 'images'])->findOrFail($id);
        return view('admin.dashboard.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with(['category', 'options.values', 'images'])->findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.dashboard.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'description' => 'required|string',
            'description_ar' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'specifications' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'primary_image' => 'nullable|integer|min:0',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'integer|exists:product_images,id',
            // SEO Fields
            'meta_title' => 'nullable|string|max:60',
            'meta_title_ar' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_description_ar' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'og_title' => 'nullable|string|max:60',
            'og_title_ar' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:300',
            'og_description_ar' => 'nullable|string|max:300',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_title_ar' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:200',
            'twitter_description_ar' => 'nullable|string|max:200',
            'twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:50',
            'structured_data' => 'nullable|string'
        ]);

        try {
            $product = Product::findOrFail($id);
            $data = $request->all();

            // Set is_active to true by default
            $data['is_active'] = true;

            // Handle specifications as JSON
            if ($request->filled('specifications')) {
                $specifications = json_decode($request->specifications, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['specifications'] = $specifications;
                } else {
                    $data['specifications'] = null;
                }
            }

            // Handle structured data as JSON
            if ($request->filled('structured_data')) {
                $structuredData = json_decode($request->structured_data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['structured_data'] = $structuredData;
                } else {
                    $data['structured_data'] = null;
                }
            }

            // Handle SEO image uploads
            if ($request->hasFile('og_image')) {
                // Delete old OG image if exists
                if ($product->og_image && Storage::disk('public')->exists(str_replace('storage/', '', $product->og_image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $product->og_image));
                }
                $ogImagePath = $request->file('og_image')->store('products/seo', 'public');
                $data['og_image'] = 'storage/' . $ogImagePath;
            }

            if ($request->hasFile('twitter_image')) {
                // Delete old Twitter image if exists
                if ($product->twitter_image && Storage::disk('public')->exists(str_replace('storage/', '', $product->twitter_image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $product->twitter_image));
                }
                $twitterImagePath = $request->file('twitter_image')->store('products/seo', 'public');
                $data['twitter_image'] = 'storage/' . $twitterImagePath;
            }

            // Set default robots if not provided
            if (empty($data['robots'])) {
                $data['robots'] = 'index,follow';
            }

            // Generate slug if name changed and slug is empty
            if ($product->isDirty('name') && empty($data['slug'])) {
                $data['slug'] = Product::generateSlug($data['name']);
            }

            // Remove image-related fields from data
            unset($data['images'], $data['primary_image'], $data['existing_images']);

            $product->update($data);

            // Handle image updates
            $this->handleImageUpdates($product, $request);

            return redirect()->route('admin.products.index')
                ->with('success', trans('products.product_updated_successfully'));
        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', trans('products.error_updating_product') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', trans('products.product_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', trans('products.error_deleting_product'));
        }
    }

    /**
     * Handle multiple image uploads for a product
     */
    private function handleImageUploads(Product $product, array $images, $primaryImageIndex = 0)
    {
        $uploadedImages = [];

        foreach ($images as $index => $image) {
            $imageName = time() . '_' . $index . '_' . $image->getClientOriginalName();
            $imagePath = 'uploads/products/' . $imageName;
            $image->move(public_path('uploads/products'), $imageName);

            $uploadedImage = $product->images()->create([
                'image_path' => $imagePath,
                'alt_text' => $product->name,
                'sort_order' => $product->images()->count()
            ]);

            $uploadedImages[] = $uploadedImage;
        }

        // Set main image_url if specified
        if ($primaryImageIndex !== null && $primaryImageIndex !== '') {
            if (is_numeric($primaryImageIndex) && isset($uploadedImages[$primaryImageIndex])) {
                $product->update(['image_url' => $uploadedImages[$primaryImageIndex]->image_path]);
            }
        } elseif (count($uploadedImages) > 0 && !$product->image_url) {
            // If no main image is set and this is the first upload, set the first image as main
            $product->update(['image_url' => $uploadedImages[0]->image_path]);
        }
    }

    /**
     * Handle image updates for a product
     */
private function handleImageUpdates(Product $product, Request $request)
    {
        // Handle existing images - remove those not in the list
        if ($request->has('existing_images')) {
            $existingImageIds = $request->input('existing_images', []);
            $product->images()->whereNotIn('id', $existingImageIds)->delete();
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'), $request->input('primary_image', 0));
        }

        // Update main image_url
        if ($request->has('primary_image') && $request->input('primary_image')) {
            $primaryImageValue = $request->input('primary_image');

            // Set the selected image as main image_url
            if (is_numeric($primaryImageValue)) {
                // Existing image ID
                $selectedImage = $product->images()->where('id', $primaryImageValue)->first();
                if ($selectedImage) {
                    $product->update(['image_url' => $selectedImage->image_path]);
                }
            } elseif (str_starts_with($primaryImageValue, 'new_')) {
                // New image index
                $newIndex = (int) str_replace('new_', '', $primaryImageValue);
                $newImages = $product->images()->orderBy('created_at', 'desc')->get();
                if (isset($newImages[$newIndex])) {
                    $product->update(['image_url' => $newImages[$newIndex]->image_path]);
                }
            }
        }
    }
}
