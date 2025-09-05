<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'designer_id' => 'nullable|integer|exists:designers,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'service_type' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:30|max:480', // 30 minutes to 8 hours
            'location' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
            'order_id' => 'nullable|integer|exists:orders,id',
            'order_notes' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'designer_id.exists' => 'Selected designer does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Please enter a valid time format (HH:MM).',
            'service_type.required' => 'Service type is required.',
            'duration.min' => 'Duration must be at least 30 minutes.',
            'duration.max' => 'Duration cannot exceed 8 hours.',
            'order_id.exists' => 'Selected order does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'designer_id' => 'designer',
            'appointment_date' => 'appointment date',
            'appointment_time' => 'appointment time',
            'service_type' => 'service type',
            'description' => 'description',
            'duration' => 'duration',
            'location' => 'location',
            'notes' => 'notes',
            'order_id' => 'order',
            'order_notes' => 'order notes'
        ];
    }
}
