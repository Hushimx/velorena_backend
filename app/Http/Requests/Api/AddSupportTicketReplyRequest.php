<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AddSupportTicketReplyRequest extends FormRequest
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
            'message' => 'required|string|max:5000',
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
            'message.required' => 'The message field is required.',
            'message.max' => 'The message may not be greater than 5000 characters.',
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
            'message' => 'reply message',
            'attachments' => 'file attachments',
            'attachments.*' => 'attachment file',
        ];
    }
}