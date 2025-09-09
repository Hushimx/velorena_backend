<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'designer_id' => $this->designer_id,
            'appointment_date' => $this->appointment_date?->format('Y-m-d'),
            'appointment_time' => $this->appointment_time,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'duration' => $this->duration,
            'location' => $this->location,
            'notes' => $this->notes,
            'status' => $this->status,
            'order_id' => $this->order_id,
            'order_notes' => $this->order_notes,
            'designer' => new DesignerResource($this->whenLoaded('designer')),
            'user' => new UserResource($this->whenLoaded('user')),
            'order' => new OrderResource($this->whenLoaded('order')),
            'meeting' => [
                'type' => $this->getMeetingType(),
                'url' => $this->getMeetingUrl(),
                'host_url' => $this->getHostMeetingUrl(),
                'has_zoom' => $this->hasZoomMeeting(),
                'has_google_meet' => $this->hasGoogleMeet(),
                'zoom_meeting_id' => $this->zoom_meeting_id,
                'zoom_meeting_url' => $this->zoom_meeting_url,
                'zoom_start_url' => $this->zoom_start_url,
                'google_meet_id' => $this->google_meet_id,
                'google_meet_link' => $this->google_meet_link,
                'meeting_created_at' => $this->meet_created_at?->toISOString(),
                'zoom_meeting_created_at' => $this->zoom_meeting_created_at?->toISOString(),
            ],
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
