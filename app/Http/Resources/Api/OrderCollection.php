<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => [
                'orders' => $this->collection,
                'pagination' => [
                    'current_page' => $this->currentPage(),
                    'last_page' => $this->lastPage(),
                    'per_page' => $this->perPage(),
                    'total' => $this->total(),
                    'from' => $this->firstItem(),
                    'to' => $this->lastItem(),
                ]
            ]
        ];
    }
}
