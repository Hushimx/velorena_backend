<?php

namespace App\Http\Controllers;

use App\Models\Design;
// ProductDesign removed - designs are now order-level only
use App\Services\DesignApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    /**
     * Display design selection page
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        
        $query = Design::active();
        
        if ($search) {
            $query->search($search);
        }
        
        if ($category) {
            $query->byCategory($category);
        }
        
        $designs = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Design::active()->distinct()->pluck('category')->filter()->values();
        
        return view('designs.index', compact('designs', 'categories', 'search', 'category'));
    }
    
    /**
     * Show design selection for a specific product
     */
    public function selectForProduct(Request $request, $productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        
        // Designs are now managed at order level, not product level
        $selectedDesigns = [];
        $designNotes = [];
        
        $query = Design::active();
        
        if ($search) {
            $query->search($search);
        }
        
        if ($category) {
            $query->byCategory($category);
        }
        
        $designs = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Design::active()->distinct()->pluck('category')->filter()->values();
        
        return view('designs.select-for-product', compact(
            'product', 'designs', 'categories', 'search', 'category', 
            'selectedDesigns', 'designNotes'
        ));
    }
    
    /**
     * Save selected designs for a product
     */
    public function saveDesignsForProduct(Request $request, $productId)
    {
        $request->validate([
            'design_ids' => 'required|array|min:1',
            'design_ids.*' => 'integer|exists:designs,id',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:1000'
        ]);
        
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please login to select designs');
        }
        
        $user = Auth::user();
        $designIds = $request->get('design_ids');
        $notes = $request->get('notes', []);
        
        try {
            // Designs are now managed at order level, not product level
            // This functionality has been moved to cart designs
            
            return redirect()->back()->with('info', 
                'Design management has been moved to the cart level. Designs will be automatically included when you create an order.');
                
        } catch (\Exception $e) {
            Log::error('Design management moved to cart level', [
                'user_id' => $user->id,
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('info', 'Design management has been moved to the cart level.');
        }
    }
    
    /**
     * Remove design from product selection
     */
    public function removeDesignFromProduct(Request $request, $productId, $designId)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please login to manage designs');
        }
        
        // Designs are now managed at order level, not product level
        return redirect()->back()->with('info', 'Design management has been moved to the cart level.');
    }
    
    /**
     * Sync designs from Freepik API
     */
    public function syncFromApi(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);
        
        try {
            $apiService = new DesignApiService();
            $search = $request->get('search');
            $category = $request->get('category');
            $limit = $request->get('limit', 50);
            
            if ($search) {
                $result = $apiService->searchDesigns($search, ['limit' => $limit]);
            } elseif ($category) {
                $result = $apiService->getDesignsByCategory($category, ['limit' => $limit]);
            } else {
                $result = $apiService->syncDesigns($limit);
            }
            
            if ($result === false) {
                return redirect()->back()->with('error', 'Failed to sync designs from Freepik API');
            }
            
            $message = 'Designs synced successfully from Freepik API';
            if (is_numeric($result)) {
                $message .= ' (' . $result . ' designs added)';
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Failed to sync designs from Freepik API', [
                'search' => $search,
                'category' => $category,
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to sync designs: ' . $e->getMessage());
        }
    }
}
