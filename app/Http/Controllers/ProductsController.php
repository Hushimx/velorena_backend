<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
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
            'description' => 'required|string',
            'description_ar' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'specifications' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'primary_image' => 'nullable|integer|min:0'
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
            \Log::error('Product creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
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
            'description' => 'required|string',
            'description_ar' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'specifications' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'primary_image' => 'nullable|integer|min:0',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'integer|exists:product_images,id'
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

            // Remove image-related fields from data
            unset($data['images'], $data['primary_image'], $data['existing_images']);

            $product->update($data);

            // Handle image updates
            $this->handleImageUpdates($product, $request);

            return redirect()->route('admin.products.index')
                ->with('success', trans('products.product_updated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
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
                'is_primary' => false, // Will be set later if needed
                'sort_order' => $product->images()->count()
            ]);
            
            $uploadedImages[] = $uploadedImage;
        }
        
        // Set primary image if specified
        if ($primaryImageIndex !== null && $primaryImageIndex !== '') {
            if (is_numeric($primaryImageIndex) && isset($uploadedImages[$primaryImageIndex])) {
                $uploadedImages[$primaryImageIndex]->update(['is_primary' => true]);
            }
        } elseif (count($uploadedImages) > 0 && $product->images()->where('is_primary', true)->count() === 0) {
            // If no primary image is set and this is the first upload, set the first image as primary
            $uploadedImages[0]->update(['is_primary' => true]);
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

        // Update primary image
        if ($request->has('primary_image') && $request->input('primary_image')) {
            $primaryImageValue = $request->input('primary_image');
            
            // Reset all images to non-primary
            $product->images()->update(['is_primary' => false]);
            
            // Set the selected image as primary
            if (is_numeric($primaryImageValue)) {
                // Existing image ID
                $product->images()->where('id', $primaryImageValue)->update(['is_primary' => true]);
            } elseif (str_starts_with($primaryImageValue, 'new_')) {
                // New image index
                $newIndex = (int) str_replace('new_', '', $primaryImageValue);
                $newImages = $product->images()->orderBy('created_at', 'desc')->get();
                if (isset($newImages[$newIndex])) {
                    $newImages[$newIndex]->update(['is_primary' => true]);
                }
            }
        }
    }
}
