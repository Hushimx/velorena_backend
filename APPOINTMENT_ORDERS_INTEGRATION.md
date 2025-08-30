# Appointment-Orders Integration Solution

## Overview

This solution allows users to link their orders with appointments, enabling designers to see all products and order details when viewing appointments. This creates a seamless workflow where:

1. **Users** create orders with multiple products
2. **Users** book appointments and link them to their orders
3. **Designers** can view all products and order details when managing appointments

## Database Design

### Entity Relationship Diagram

```
Users (1) ----< Orders (1) ----< OrderItems (M) >---- Products (1)
  |                                                      |
  |                                                      |
  |                                                      |
  +----< Appointments (1) ----< AppointmentOrders (M) >----+
```

### Key Relationships

-   **Users** can have multiple **Orders**
-   **Orders** can have multiple **OrderItems** (products)
-   **Users** can have multiple **Appointments**
-   **Appointments** can be linked to multiple **Orders** (many-to-many)
-   **Designers** can view all products from linked orders

## Database Migration

### New Migration: `appointment_orders` Table

```php
// database/migrations/2025_08_23_000000_create_appointment_orders_table.php

Schema::create('appointment_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
    $table->foreignId('order_id')->constrained()->onDelete('cascade');
    $table->text('notes')->nullable(); // Additional notes for this specific order in the appointment
    $table->timestamps();

    // Ensure unique combination of appointment and order
    $table->unique(['appointment_id', 'order_id']);

    // Indexes for better performance
    $table->index(['appointment_id']);
    $table->index(['order_id']);
});
```

## Model Updates

### Appointment Model

Added relationships and helper methods:

```php
// Relationships
public function orders(): BelongsToMany
{
    return $this->belongsToMany(Order::class, 'appointment_orders')
                ->withPivot('notes')
                ->withTimestamps();
}

public function products(): HasManyThrough
{
    return $this->hasManyThrough(
        OrderItem::class,
        Order::class,
        'id',
        'order_id',
        'id',
        'id'
    )->whereIn('order_id', $this->orders->pluck('id'));
}

// Helper methods
public function linkOrder(Order $order, string $notes = null): void
public function unlinkOrder(Order $order): void
public function linkOrders(array $orderIds, array $notes = []): void
public function getTotalProductsCount(): int
public function getTotalOrderValue(): float
public function hasOrders(): bool
public function getProductsSummary(): array
```

### Order Model

Added relationship to appointments:

```php
public function appointments(): BelongsToMany
{
    return $this->belongsToMany(Appointment::class, 'appointment_orders')
                ->withPivot('notes')
                ->withTimestamps();
}
```

## API Endpoints

### AppointmentController Methods

#### 1. Create Appointment with Orders

```php
POST /api/appointments
{
    "user_id": 1,
    "designer_id": 2,
    "appointment_date": "2025-01-25",
    "appointment_time": "14:30",
    "duration_minutes": 30,
    "notes": "Client consultation",
    "order_ids": [1, 2, 3],
    "order_notes": ["Priority order", "Standard order", "Rush order"]
}
```

#### 2. Get Appointment Details

```php
GET /api/appointments/{id}
```

#### 3. Update Appointment and Orders

```php
PUT /api/appointments/{id}
{
    "order_ids": [1, 4, 5],
    "order_notes": ["Updated notes"]
}
```

#### 4. Link Additional Orders

```php
POST /api/appointments/{id}/link-orders
{
    "order_ids": [6, 7],
    "order_notes": ["Additional products"]
}
```

#### 5. Unlink Orders

```php
POST /api/appointments/{id}/unlink-orders
{
    "order_ids": [1, 2]
}
```

## Frontend Components

### Livewire Components

#### 1. BookAppointmentWithOrders

-   Allows users to select orders when booking appointments
-   Shows available time slots
-   Displays order summaries and totals

#### 2. DesignerAppointmentsWithOrders

-   Shows designers all appointments with linked orders
-   Displays product summaries and order details
-   Provides action buttons for appointment management

### Key Features

1. **Order Selection**: Users can select multiple orders to link with appointments
2. **Product Summary**: Shows total products, quantities, and values
3. **Order Notes**: Each order can have specific notes for the appointment
4. **Real-time Updates**: Livewire components provide real-time interaction
5. **Filtering**: Designers can filter appointments by status and date

## Usage Examples

### 1. User Booking Appointment with Orders

```php
// In a controller or Livewire component
$appointment = Appointment::create([
    'user_id' => $userId,
    'designer_id' => $designerId,
    'appointment_date' => $date,
    'appointment_time' => $time,
    'duration_minutes' => 30,
    'notes' => 'Consultation for wedding dress',
    'status' => 'pending'
]);

// Link orders to appointment
$appointment->linkOrders([1, 2, 3], [
    'Priority wedding dress order',
    'Accessories order',
    'Alterations order'
]);
```

### 2. Designer Viewing Appointment with Products

```php
// In designer dashboard
$appointment = Appointment::with([
    'user',
    'orders.items.product',
    'orders.items.product.options.values'
])->find($appointmentId);

// Get product summary
$productsSummary = $appointment->getProductsSummary();
$totalProducts = $appointment->getTotalProductsCount();
$totalValue = $appointment->getTotalOrderValue();
```

### 3. API Response Example

```json
{
    "success": true,
    "data": {
        "appointment": {
            "id": 1,
            "user_id": 1,
            "designer_id": 2,
            "appointment_date": "2025-01-25",
            "appointment_time": "14:30:00",
            "status": "pending",
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com"
            },
            "orders": [
                {
                    "id": 1,
                    "order_number": "ORD202501001",
                    "total": 1500.0,
                    "pivot": {
                        "notes": "Priority wedding dress order"
                    },
                    "items": [
                        {
                            "id": 1,
                            "product": {
                                "name": "Wedding Dress",
                                "price": 1200.0
                            },
                            "quantity": 1,
                            "total_price": 1200.0
                        }
                    ]
                }
            ]
        },
        "products_summary": {
            "Wedding Dress": {
                "quantity": 1,
                "total_price": 1200.0,
                "unit_price": 1200.0
            }
        },
        "total_products_count": 1,
        "total_order_value": 1500.0,
        "linked_orders_count": 1
    }
}
```

## Implementation Tips

### 1. Database Transactions

Always use database transactions when creating appointments and linking orders:

```php
DB::beginTransaction();
try {
    $appointment = Appointment::create($data);
    $appointment->orders()->attach($pivotData);
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

### 2. Eager Loading

Use eager loading to avoid N+1 queries:

```php
$appointments = Appointment::with([
    'user:id,name,email',
    'orders.items.product',
    'orders.items.product.options.values'
])->get();
```

### 3. Validation

Validate order ownership and availability:

```php
// Ensure user owns the orders
$userOrders = Order::where('user_id', $userId)
    ->whereIn('id', $orderIds)
    ->pluck('id');

if (count($userOrders) !== count($orderIds)) {
    throw new \Exception('Some orders do not belong to this user');
}
```

### 4. Performance Optimization

-   Use database indexes on frequently queried columns
-   Implement caching for appointment availability
-   Use pagination for large datasets

### 5. Security Considerations

-   Validate user permissions before linking orders
-   Ensure designers can only see their assigned appointments
-   Sanitize all input data

## Migration Steps

1. **Run the migration**:

    ```bash
    php artisan migrate
    ```

2. **Update existing models** with the new relationships

3. **Test the integration** with sample data

4. **Deploy to production** after thorough testing

## Benefits

1. **Better User Experience**: Users can easily link their orders with appointments
2. **Designer Efficiency**: Designers can see all relevant products and order details
3. **Data Integrity**: Proper relationships ensure data consistency
4. **Scalability**: The solution can handle multiple orders per appointment
5. **Flexibility**: Orders can be linked/unlinked as needed

## Future Enhancements

1. **Order Status Tracking**: Track order status changes in relation to appointments
2. **Automated Notifications**: Send notifications when orders are linked to appointments
3. **Analytics**: Track appointment-order relationships for business insights
4. **Bulk Operations**: Allow bulk linking/unlinking of orders
5. **Order Recommendations**: Suggest relevant orders based on appointment type
