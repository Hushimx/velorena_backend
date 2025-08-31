<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1|max:50',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
            'items.*.options' => 'nullable|array|sometimes',
            'items.*.options.*' => 'integer|exists:option_values,id',
            'items.*.notes' => 'nullable|string|max:200'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.max' => 'Maximum 50 items allowed per order.',
            'items.*.product_id.required' => 'Product ID is required.',
            'items.*.product_id.exists' => 'Selected product does not exist.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.quantity.max' => 'Quantity cannot exceed 100.',
            'items.*.options.*.exists' => 'Selected option does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'phone' => 'phone number',
            'shipping_address' => 'shipping address',
            'billing_address' => 'billing address',
            'items' => 'order items',
            'items.*.product_id' => 'product',
            'items.*.quantity' => 'quantity',
            'items.*.options' => 'product options',
            'items.*.notes' => 'item notes'
        ];
    }
}
