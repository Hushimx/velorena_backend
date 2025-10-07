<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'phone' => $this->phone,
            
            // Address information
            'address_id' => $this->address_id,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'shipping_contact_name' => $this->shipping_contact_name,
            'shipping_contact_phone' => $this->shipping_contact_phone,
            'shipping_city' => $this->shipping_city,
            'shipping_district' => $this->shipping_district,
            'shipping_street' => $this->shipping_street,
            'shipping_house_description' => $this->shipping_house_description,
            'shipping_postal_code' => $this->shipping_postal_code,
            
            'notes' => $this->notes,
            'status' => $this->status,
            'payment_status' => $this->getPaymentStatus(),
            'can_make_payment' => $this->canMakePayment(),
            'subtotal' => (float) $this->subtotal,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'items_count' => $this->whenLoaded('items', function () {
                return $this->items->count();
            }),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'address' => $this->whenLoaded('address'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
