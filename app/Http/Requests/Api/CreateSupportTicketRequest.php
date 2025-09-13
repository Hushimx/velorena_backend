<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupportTicketRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request,bug_report',
            'attachments' => 'nullable|array|max:5', // Max 5 attachments
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:10240', // 10MB max per file
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'subject.required' => 'The subject field is required.',
            'subject.max' => 'The subject may not be greater than 255 characters.',
            'description.required' => 'The description field is required.',
            'description.max' => 'The description may not be greater than 5000 characters.',
            'priority.required' => 'The priority field is required.',
            'priority.in' => 'The priority must be one of: low, medium, high, urgent.',
            'category.required' => 'The category field is required.',
            'category.in' => 'The category must be one of: technical, billing, general, feature_request, bug_report.',
            'attachments.array' => 'Attachments must be an array.',
            'attachments.max' => 'You can upload a maximum of 5 attachments.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.mimes' => 'Attachments must be one of the following types: pdf, jpg, jpeg, png, doc, docx, txt.',
            'attachments.*.max' => 'Each attachment may not be greater than 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'subject' => 'ticket subject',
            'description' => 'ticket description',
            'priority' => 'ticket priority',
            'category' => 'ticket category',
            'attachments' => 'file attachments',
            'attachments.*' => 'attachment file',
        ];
    }
}