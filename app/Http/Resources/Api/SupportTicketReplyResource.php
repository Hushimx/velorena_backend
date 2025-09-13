<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketReplyResource extends JsonResource
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
            'message' => $this->message,
            'attachments' => $this->attachments ?? [],
            'author_type' => $this->author_type,
            'author_name' => $this->author_name,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}