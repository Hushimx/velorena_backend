<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject' => $this->subject,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'category' => $this->category,
            'attachments' => $this->attachments ?? [],
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Simple relationships
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->full_name ?? $this->user->company_name,
                    'email' => $this->user->email,
                ];
            }),
            
            'assigned_admin' => $this->whenLoaded('assignedAdmin', function () {
                return [
                    'id' => $this->assignedAdmin->id,
                    'name' => $this->assignedAdmin->name,
                ];
            }),
        ];
    }
}