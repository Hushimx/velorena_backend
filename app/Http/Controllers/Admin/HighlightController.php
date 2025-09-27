<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HighlightController extends Controller
{
    public function index()
    {
        $highlights = Highlight::withCount('products')->ordered()->get();
        return view('admin.dashboard.highlights.index', compact('highlights'));
    }

    public function create()
    {
        return view('admin.dashboard.highlights.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        $data['slug'] = Highlight::generateSlug($request->name);

        Highlight::create($data);

        return redirect()->route('admin.highlights.index')
            ->with('success', 'Highlight created successfully');
    }

    public function show(Highlight $highlight)
    {
        $highlight->load('products');
        return view('admin.dashboard.highlights.show', compact('highlight'));
    }

    public function edit(Highlight $highlight)
    {
        return view('admin.dashboard.highlights.edit', compact('highlight'));
    }

    public function update(Request $request, Highlight $highlight)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();

        // Update slug if name changed
        if ($highlight->name !== $request->name) {
            $data['slug'] = Highlight::generateSlug($request->name);
        }

        $highlight->update($data);

        return redirect()->route('admin.highlights.index')
            ->with('success', 'Highlight updated successfully');
    }

    public function destroy(Highlight $highlight)
    {
        $highlight->delete();

        return redirect()->route('admin.highlights.index')
            ->with('success', 'Highlight deleted successfully');
    }

    /**
     * Show form to assign highlights to a product
     */
    public function assignToProduct($id)
    {
        $product = Product::findOrFail($id);
        $highlights = Highlight::active()->ordered()->get();
        $productHighlights = $product->highlights->pluck('id')->toArray();

        return view('admin.dashboard.products.assign-highlights', compact('product', 'highlights', 'productHighlights'));
    }

    /**
     * Store highlight assignments for a product
     */
    public function storeProductHighlights(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'highlights' => 'nullable|array',
            'highlights.*' => 'integer|exists:highlights,id',
            'sort_orders' => 'nullable|array',
            'sort_orders.*' => 'integer|min:0'
        ]);

        DB::transaction(function () use ($request, $product) {
            // Remove existing highlights
            $product->highlights()->detach();

            // Add new highlights with sort orders
            if ($request->has('highlights') && is_array($request->highlights)) {
                $highlightsData = [];
                foreach ($request->highlights as $index => $highlightId) {
                    $sortOrder = $request->sort_orders[$highlightId] ?? $index;
                    $highlightsData[$highlightId] = ['sort_order' => $sortOrder];
                }
                $product->highlights()->attach($highlightsData);
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product highlights updated successfully');
    }
}
