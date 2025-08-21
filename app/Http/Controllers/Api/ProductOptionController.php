<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductOptionController extends Controller
{
    /**
     * Display options for a specific product
     */
    public function index(Request $request, Product $product): JsonResponse
    {
        $options = $product->options()
            ->with('values')
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    /**
     * Store a new option for a product
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'type' => 'required|in:select,radio,checkbox,text,number',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'additional_data' => 'nullable|array',
            'values' => 'nullable|array',
            'values.*.value' => 'required_with:values|string|max:255',
            'values.*.value_ar' => 'nullable|string|max:255',
            'values.*.price_adjustment' => 'nullable|numeric',
            'values.*.is_active' => 'boolean',
            'values.*.sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $optionData = $validator->validated();
            $values = $optionData['values'] ?? [];
            unset($optionData['values']);

            $option = $product->options()->create($optionData);

            // Create option values if provided
            foreach ($values as $valueData) {
                $option->values()->create($valueData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product option created successfully',
                'data' => $option->load('values')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product option',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified option
     */
    public function show(Product $product, ProductOption $option): JsonResponse
    {
        if ($option->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Option does not belong to this product'
            ], 404);
        }

        $option->load('values');

        return response()->json([
            'success' => true,
            'data' => $option
        ]);
    }

    /**
     * Update the specified option
     */
    public function update(Request $request, Product $product, ProductOption $option): JsonResponse
    {
        if ($option->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Option does not belong to this product'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'type' => 'sometimes|required|in:select,radio,checkbox,text,number',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'additional_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $option->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product option updated successfully',
            'data' => $option->load('values')
        ]);
    }

    /**
     * Remove the specified option
     */
    public function destroy(Product $product, ProductOption $option): JsonResponse
    {
        if ($option->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Option does not belong to this product'
            ], 404);
        }

        $option->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product option deleted successfully'
        ]);
    }

    /**
     * Add a value to an option
     */
    public function addValue(Request $request, Product $product, ProductOption $option): JsonResponse
    {
        if ($option->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Option does not belong to this product'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:255',
            'value_ar' => 'nullable|string|max:255',
            'price_adjustment' => 'nullable|numeric',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'additional_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $value = $option->values()->create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Option value added successfully',
            'data' => $value
        ], 201);
    }

    /**
     * Update an option value
     */
    public function updateValue(Request $request, Product $product, ProductOption $option, OptionValue $value): JsonResponse
    {
        if ($option->product_id !== $product->id || $value->product_option_id !== $option->id) {
            return response()->json([
                'success' => false,
                'message' => 'Value does not belong to this option'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'value' => 'sometimes|required|string|max:255',
            'value_ar' => 'nullable|string|max:255',
            'price_adjustment' => 'nullable|numeric',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'additional_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $value->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Option value updated successfully',
            'data' => $value
        ]);
    }

    /**
     * Remove an option value
     */
    public function removeValue(Product $product, ProductOption $option, OptionValue $value): JsonResponse
    {
        if ($option->product_id !== $product->id || $value->product_option_id !== $option->id) {
            return response()->json([
                'success' => false,
                'message' => 'Value does not belong to this option'
            ], 404);
        }

        $value->delete();

        return response()->json([
            'success' => true,
            'message' => 'Option value removed successfully'
        ]);
    }
}
