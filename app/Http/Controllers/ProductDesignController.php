<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Design;
use App\Models\ProductDesign;
use App\Services\DesignApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductDesignController extends Controller
{
    /**
     * Display the design selection page for a product
     */
    public function index(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $user = Auth::user();

        // Get designs already selected for this product by this user
        $selectedDesigns = ProductDesign::forUser($user->id)
            ->forProduct($product->id)
            ->with('design')
            ->orderedByPriority()
            ->get();

        // Get available designs
        $designs = Design::active()->orderBy('created_at', 'desc')->take(50)->get();
        $categories = Design::active()->distinct()->pluck('category')->filter()->values();

        return view('users.products.designs', compact('product', 'designs', 'categories', 'selectedDesigns'));
    }

    /**
     * Store selected designs for a product
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'designs' => 'required|array|min:1',
            'designs.*.design_id' => 'required|exists:designs,id',
            'designs.*.notes' => 'nullable|string|max:500',
            'designs.*.priority' => 'required|integer|min:1'
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Remove existing selections for this product and user
            ProductDesign::forUser($user->id)->forProduct($product->id)->delete();

            // Add new selections
            foreach ($request->designs as $designData) {
                ProductDesign::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'design_id' => $designData['design_id'],
                    'notes' => $designData['notes'] ?? '',
                    'priority' => $designData['priority']
                ]);
            }

            DB::commit();

            return redirect()->route('cart.index')
                ->with('success', 'Designs selected successfully for ' . $product->name . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save design selections: ' . $e->getMessage());
        }
    }

    /**
     * Remove a design selection for a product
     */
    public function destroy(Product $product, Design $design)
    {
        $user = Auth::user();

        ProductDesign::forUser($user->id)
            ->forProduct($product->id)
            ->where('design_id', $design->id)
            ->delete();

        return back()->with('success', 'Design removed successfully!');
    }

    /**
     * Sync designs from API
     */
    public function syncDesigns(Request $request)
    {
        try {
            $apiService = new DesignApiService();
            $search = $request->get('search');
            $category = $request->get('category');

            if ($search) {
                $apiService->searchDesigns($search);
            } elseif ($category) {
                $apiService->getDesignsByCategory($category);
            } else {
                $apiService->syncDesigns(50);
            }

            return response()->json([
                'success' => true,
                'message' => 'Designs synced successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync designs: ' . $e->getMessage()
            ], 500);
        }
    }
}
