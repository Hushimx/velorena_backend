# Orders API - Laravel Best Practices Implementation

## Overview

This document describes the refactored Orders API implementation following Laravel best practices, including:

-   **Form Requests** for validation
-   **API Resources** for response formatting
-   **Service Layer** for business logic
-   **Proper error handling**
-   **Clean architecture principles**

## Architecture

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   └── OrderController.php          # Thin controller with dependency injection
│   ├── Requests/Api/
│   │   ├── StoreOrderRequest.php        # Validation for creating orders
│   │   ├── AddOrderItemRequest.php      # Validation for adding items
│   │   └── RemoveOrderItemRequest.php   # Validation for removing items
│   └── Resources/Api/
│       ├── OrderResource.php            # Order response formatting
│       ├── OrderCollection.php          # Order collection with pagination
│       ├── OrderItemResource.php        # Order item response formatting
│       ├── OrderItemCollection.php      # Order items collection
│       ├── ProductResource.php          # Product response formatting
│       ├── ProductOptionResource.php    # Product option response formatting
│       └── OptionValueResource.php      # Option value response formatting
├── Services/
│   └── OrderService.php                 # Business logic layer
└── Models/
    ├── Order.php                        # Order model
    ├── OrderItem.php                    # Order item model
    └── Product.php                      # Product model
```

## Key Improvements

### 1. Form Requests (Validation)

#### StoreOrderRequest

```php
class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:20|regex:/^\+?[1-9]\d{1,14}$/',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1|max:50',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
            'items.*.options' => 'nullable|array',
            'items.*.options.*' => 'integer|exists:option_values,id',
            'items.*.notes' => 'nullable|string|max:200'
        ];
    }
}
```

**Benefits:**

-   ✅ **Separation of concerns** - Validation logic separated from controller
-   ✅ **Reusable validation** - Can be used across multiple endpoints
-   ✅ **Custom error messages** - User-friendly validation messages
-   ✅ **Custom attributes** - Better error message formatting
-   ✅ **Authorization logic** - Built-in authorization checks

### 2. API Resources (Response Formatting)

#### OrderResource

```php
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'phone' => $this->phone,
            'status' => $this->status,
            'subtotal' => (float) $this->subtotal,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'items_count' => $this->whenLoaded('items', function () {
                return $this->items->count();
            }),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
```

**Benefits:**

-   ✅ **Consistent responses** - Standardized API response format
-   ✅ **Data transformation** - Automatic type casting and formatting
-   ✅ **Conditional loading** - Only load relationships when needed
-   ✅ **Nested resources** - Proper relationship handling
-   ✅ **Type safety** - Explicit data types

### 3. Service Layer (Business Logic)

#### OrderService

```php
class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([...]);
            $subtotal = $this->addOrderItems($order, $data['items']);
            $this->updateOrderTotals($order, $subtotal);
            return $order->load(['items.product', 'items.product.options.values']);
        });
    }
}
```

**Benefits:**

-   ✅ **Single responsibility** - Each method has one clear purpose
-   ✅ **Reusable logic** - Business logic can be reused across controllers
-   ✅ **Testable** - Easy to unit test business logic
-   ✅ **Transaction handling** - Proper database transaction management
-   ✅ **Error handling** - Centralized error handling

### 4. Dependency Injection

#### Controller Constructor

```php
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}
}
```

**Benefits:**

-   ✅ **Loose coupling** - Controller doesn't directly instantiate services
-   ✅ **Testable** - Easy to mock dependencies for testing
-   ✅ **Maintainable** - Easy to swap implementations
-   ✅ **Type safety** - IDE support and type checking

## API Endpoints

### 1. Get Orders (Index)

```php
public function index(Request $request): OrderCollection
{
    $user = Auth::user();

    $query = Order::where('user_id', $user->id)
        ->with(['items.product', 'items.product.options.values']);

    // Apply filters, sorting, pagination
    $orders = $query->paginate($perPage);

    return new OrderCollection($orders);
}
```

**Response:**

```json
{
    "success": true,
    "message": "Orders retrieved successfully",
    "data": {
        "orders": [
            {
                "id": 1,
                "order_number": "ORD2025080001",
                "phone": "+966501234567",
                "status": "pending",
                "subtotal": 1500.0,
                "tax": 225.0,
                "total": 1725.0,
                "items_count": 2,
                "items": [...],
                "created_at": "2025-08-30T10:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 15,
            "total": 25,
            "from": 1,
            "to": 15
        }
    }
}
```

### 2. Create Order

```php
public function store(StoreOrderRequest $request): JsonResponse
{
    try {
        $order = $this->orderService->createOrder($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => new OrderResource($order)
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create order',
            'error' => $e->getMessage()
        ], 500);
    }
}
```

**Request Validation:**

-   ✅ **Phone validation** - International phone number format
-   ✅ **Items validation** - Minimum 1, maximum 50 items
-   ✅ **Product validation** - Product must exist
-   ✅ **Quantity validation** - Between 1 and 100
-   ✅ **Options validation** - Option values must exist

### 3. Add Item to Order

```php
public function addItem(AddOrderItemRequest $request, Order $order): JsonResponse
{
    try {
        // Authorization checks
        if (!$this->orderService->canModifyOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify order that is not pending'
            ], 400);
        }

        $updatedOrder = $this->orderService->addItemToOrder($order, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Item added to order successfully',
            'data' => new OrderResource($updatedOrder)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to add item to order',
            'error' => $e->getMessage()
        ], 500);
    }
}
```

## Error Handling

### 1. Validation Errors (422)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "phone": ["Please enter a valid phone number."],
        "items.0.product_id": ["Selected product does not exist."],
        "items.0.quantity": ["Quantity must be at least 1."]
    }
}
```

### 2. Authorization Errors (403)

```json
{
    "success": false,
    "message": "Unauthorized access to order"
}
```

### 3. Business Logic Errors (400)

```json
{
    "success": false,
    "message": "Cannot modify order that is not pending"
}
```

### 4. Server Errors (500)

```json
{
    "success": false,
    "message": "Failed to create order",
    "error": "Database connection failed"
}
```

## Testing

### 1. Unit Testing Service Layer

```php
class OrderServiceTest extends TestCase
{
    public function test_can_create_order()
    {
        $service = new OrderService();
        $data = [
            'phone' => '+966501234567',
            'items' => [
                ['product_id' => 1, 'quantity' => 2]
            ]
        ];

        $order = $service->createOrder($data);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('pending', $order->status);
    }
}
```

### 2. Feature Testing API Endpoints

```php
class OrderApiTest extends TestCase
{
    public function test_can_create_order_via_api()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/orders', [
            'phone' => '+966501234567',
            'items' => [
                ['product_id' => 1, 'quantity' => 2]
            ]
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'order_number',
                        'status',
                        'total'
                    ]
                ]);
    }
}
```

## Best Practices Implemented

### 1. **SOLID Principles**

-   ✅ **Single Responsibility** - Each class has one purpose
-   ✅ **Open/Closed** - Easy to extend without modification
-   ✅ **Liskov Substitution** - Proper inheritance and interfaces
-   ✅ **Interface Segregation** - Focused interfaces
-   ✅ **Dependency Inversion** - Depend on abstractions

### 2. **Clean Architecture**

-   ✅ **Controllers** - Handle HTTP requests/responses only
-   ✅ **Services** - Contain business logic
-   ✅ **Models** - Data access and relationships
-   ✅ **Resources** - Response formatting
-   ✅ **Requests** - Input validation

### 3. **API Design**

-   ✅ **RESTful endpoints** - Proper HTTP methods and status codes
-   ✅ **Consistent responses** - Standardized JSON structure
-   ✅ **Proper pagination** - Efficient data loading
-   ✅ **Error handling** - Comprehensive error responses
-   ✅ **Authentication** - Proper authorization checks

### 4. **Performance**

-   ✅ **Eager loading** - Prevent N+1 queries
-   ✅ **Database transactions** - Data integrity
-   ✅ **Efficient queries** - Optimized database operations
-   ✅ **Caching ready** - Easy to add caching layer

### 5. **Maintainability**

-   ✅ **Clear separation** - Logical code organization
-   ✅ **Type hints** - Better IDE support
-   ✅ **Documentation** - Comprehensive API docs
-   ✅ **Testing** - Unit and feature tests
-   ✅ **Error handling** - Proper exception handling

## Usage Examples

### 1. Create Order

```bash
curl -X POST "http://localhost:8000/api/orders" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+966501234567",
    "shipping_address": "123 Main St, Riyadh",
    "items": [
      {
        "product_id": 1,
        "quantity": 2,
        "options": [1, 2],
        "notes": "Large size please"
      }
    ]
  }'
```

### 2. Get Orders with Filters

```bash
curl -X GET "http://localhost:8000/api/orders?status=pending&per_page=10&sort_by=created_at&sort_order=desc" \
  -H "Authorization: Bearer {token}"
```

### 3. Add Item to Order

```bash
curl -X POST "http://localhost:8000/api/orders/1/items" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 2,
    "quantity": 1,
    "options": [3, 4],
    "notes": "Blue color preferred"
  }'
```

This refactored implementation follows Laravel best practices and provides a solid foundation for a production-ready API.
