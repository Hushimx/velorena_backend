<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
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
            'designer_id' => 'sometimes|integer|exists:designers,id',
            'appointment_date' => 'sometimes|date|after:today',
            'appointment_time' => 'sometimes|date_format:H:i',
            'service_type' => 'sometimes|string|max:100',
            'description' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:30|max:480',
            'location' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
            'status' => 'sometimes|string|in:pending,confirmed,cancelled,completed',
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
            'appointment_date.after' => 'Appointment date must be in the future.',
            'appointment_time.date_format' => 'Please enter a valid time format (HH:MM).',
            'duration.min' => 'Duration must be at least 30 minutes.',
            'duration.max' => 'Duration cannot exceed 8 hours.',
            'status.in' => 'Status must be one of: pending, confirmed, cancelled, completed.',
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
            'status' => 'status',
            'order_id' => 'order',
            'order_notes' => 'order notes'
        ];
    }
}
