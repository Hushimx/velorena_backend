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
            'phone.required' => __('validation.phone.required'),
            'phone.regex' => __('validation.phone.regex'),
            'items.required' => __('validation.items.required'),
            'items.min' => __('validation.items.min'),
            'items.max' => __('validation.items.max'),
            'items.*.product_id.required' => __('validation.product_id.required'),
            'items.*.product_id.exists' => __('validation.product_id.exists'),
            'items.*.quantity.required' => __('validation.quantity.required'),
            'items.*.quantity.min' => __('validation.quantity.min'),
            'items.*.quantity.max' => __('validation.quantity.max'),
            'items.*.options.*.exists' => __('validation.option.exists'),
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
