# Orders API Documentation

## Overview

The Orders API allows users to manage their orders, including creating new orders, viewing order details, adding/removing items, and deleting orders. All endpoints require authentication using Bearer tokens.

## Base URL

```
http://localhost:8000/api
```

## Authentication

All orders endpoints require authentication. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. Get All Orders

**GET** `/orders`

Retrieve all orders for the authenticated user with optional filtering and pagination.

#### Query Parameters

| Parameter    | Type    | Required | Description                                                                            |
| ------------ | ------- | -------- | -------------------------------------------------------------------------------------- |
| `status`     | string  | No       | Filter by order status (pending, confirmed, processing, shipped, delivered, cancelled) |
| `search`     | string  | No       | Search in order number or phone                                                        |
| `sort_by`    | string  | No       | Sort field (created_at, updated_at, total, status) - default: created_at               |
| `sort_order` | string  | No       | Sort direction (asc, desc) - default: desc                                             |
| `per_page`   | integer | No       | Number of items per page - default: 15                                                 |

#### Example Request

```bash
curl -X GET "http://localhost:8000/api/orders?status=pending&sort_by=created_at&sort_order=desc&per_page=10" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "data": [
        {
            "id": 1,
            "order_number": "ORD2024120001",
            "phone": "+1234567890",
            "shipping_address": "123 Main St, City, Country",
            "billing_address": "123 Main St, City, Country",
            "notes": "Please deliver in the morning",
            "status": "pending",
            "subtotal": 150.0,
            "tax": 22.5,
            "total": 172.5,
            "items_count": 2,
            "items": [
                {
                    "id": 1,
                    "product_id": 1,
                    "product": {
                        "id": 1,
                        "name": "Product Name",
                        "description": "Product description",
                        "price": 75.0,
                        "image": "product-image.jpg"
                    },
                    "quantity": 2,
                    "unit_price": 75.0,
                    "total_price": 150.0,
                    "options": [1, 2],
                    "formatted_options": "Size: Large, Color: Blue",
                    "notes": "Extra large size"
                }
            ],
            "created_at": "2024-12-01T10:00:00.000000Z",
            "updated_at": "2024-12-01T10:00:00.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/orders?page=1",
        "last": "http://localhost:8000/api/orders?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

### 2. Create New Order

**POST** `/orders`

Create a new order with items.

#### Request Body

```json
{
    "phone": "+1234567890",
    "shipping_address": "123 Main St, City, Country",
    "billing_address": "123 Main St, City, Country",
    "notes": "Please deliver in the morning",
    "items": [
        {
            "product_id": 1,
            "quantity": 2,
            "options": [1, 2],
            "notes": "Extra large size"
        },
        {
            "product_id": 2,
            "quantity": 1,
            "options": [],
            "notes": "Standard size"
        }
    ]
}
```

#### Validation Rules

| Field                | Type    | Required | Validation                                  |
| -------------------- | ------- | -------- | ------------------------------------------- |
| `phone`              | string  | Yes      | Required, max 20 chars, phone number format |
| `shipping_address`   | string  | No       | Max 500 chars                               |
| `billing_address`    | string  | No       | Max 500 chars                               |
| `notes`              | string  | No       | Max 500 chars                               |
| `items`              | array   | Yes      | Required, min 1, max 50 items               |
| `items.*.product_id` | integer | Yes      | Required, exists in products table          |
| `items.*.quantity`   | integer | Yes      | Required, min 1, max 100                    |
| `items.*.options`    | array   | No       | Array of option value IDs                   |
| `items.*.options.*`  | integer | No       | Exists in option_values table               |
| `items.*.notes`      | string  | No       | Max 200 chars                               |

#### Example Request

```bash
curl -X POST "http://localhost:8000/api/orders" \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone": "+1234567890",
    "shipping_address": "123 Main St, City, Country",
    "billing_address": "123 Main St, City, Country",
    "notes": "Please deliver in the morning",
    "items": [
      {
        "product_id": 1,
        "quantity": 2,
        "options": [1, 2],
        "notes": "Extra large size"
      }
    ]
  }'
```

#### Example Response

```json
{
    "success": true,
    "message": "Order created successfully",
    "data": {
        "id": 1,
        "order_number": "ORD2024120001",
        "phone": "+1234567890",
        "shipping_address": "123 Main St, City, Country",
        "billing_address": "123 Main St, City, Country",
        "notes": "Please deliver in the morning",
        "status": "pending",
        "subtotal": 150.0,
        "tax": 22.5,
        "total": 172.5,
        "items_count": 1,
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product": {
                    "id": 1,
                    "name": "Product Name",
                    "description": "Product description",
                    "price": 75.0,
                    "image": "product-image.jpg"
                },
                "quantity": 2,
                "unit_price": 75.0,
                "total_price": 150.0,
                "options": [1, 2],
                "formatted_options": "Size: Large, Color: Blue",
                "notes": "Extra large size"
            }
        ],
        "created_at": "2024-12-01T10:00:00.000000Z",
        "updated_at": "2024-12-01T10:00:00.000000Z"
    }
}
```

### 3. Get Specific Order

**GET** `/orders/{order_id}`

Retrieve details of a specific order.

#### Path Parameters

| Parameter  | Type    | Required | Description         |
| ---------- | ------- | -------- | ------------------- |
| `order_id` | integer | Yes      | The ID of the order |

#### Example Request

```bash
curl -X GET "http://localhost:8000/api/orders/1" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Order retrieved successfully",
    "data": {
        "id": 1,
        "order_number": "ORD2024120001",
        "phone": "+1234567890",
        "shipping_address": "123 Main St, City, Country",
        "billing_address": "123 Main St, City, Country",
        "notes": "Please deliver in the morning",
        "status": "pending",
        "subtotal": 150.0,
        "tax": 22.5,
        "total": 172.5,
        "items_count": 1,
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product": {
                    "id": 1,
                    "name": "Product Name",
                    "description": "Product description",
                    "price": 75.0,
                    "image": "product-image.jpg"
                },
                "quantity": 2,
                "unit_price": 75.0,
                "total_price": 150.0,
                "options": [1, 2],
                "formatted_options": "Size: Large, Color: Blue",
                "notes": "Extra large size"
            }
        ],
        "created_at": "2024-12-01T10:00:00.000000Z",
        "updated_at": "2024-12-01T10:00:00.000000Z"
    }
}
```

### 4. Add Item to Order

**POST** `/orders/{order_id}/items`

Add a new item to an existing order (only works for pending orders).

#### Path Parameters

| Parameter  | Type    | Required | Description         |
| ---------- | ------- | -------- | ------------------- |
| `order_id` | integer | Yes      | The ID of the order |

#### Request Body

```json
{
    "product_id": 3,
    "quantity": 1,
    "options": [3],
    "notes": "Added later"
}
```

#### Validation Rules

| Field        | Type    | Required | Validation                         |
| ------------ | ------- | -------- | ---------------------------------- |
| `product_id` | integer | Yes      | Required, exists in products table |
| `quantity`   | integer | Yes      | Required, min 1, max 100           |
| `options`    | array   | No       | Array of option value IDs          |
| `options.*`  | integer | No       | Exists in option_values table      |
| `notes`      | string  | No       | Max 200 chars                      |

#### Example Request

```bash
curl -X POST "http://localhost:8000/api/orders/1/items" \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "product_id": 3,
    "quantity": 1,
    "options": [3],
    "notes": "Added later"
  }'
```

#### Example Response

```json
{
    "success": true,
    "message": "Item added to order successfully",
    "data": {
        "id": 1,
        "order_number": "ORD2024120001",
        "phone": "+1234567890",
        "shipping_address": "123 Main St, City, Country",
        "billing_address": "123 Main St, City, Country",
        "notes": "Please deliver in the morning",
        "status": "pending",
        "subtotal": 225.0,
        "tax": 33.75,
        "total": 258.75,
        "items_count": 2,
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product": {
                    "id": 1,
                    "name": "Product Name",
                    "description": "Product description",
                    "price": 75.0,
                    "image": "product-image.jpg"
                },
                "quantity": 2,
                "unit_price": 75.0,
                "total_price": 150.0,
                "options": [1, 2],
                "formatted_options": "Size: Large, Color: Blue",
                "notes": "Extra large size"
            },
            {
                "id": 2,
                "product_id": 3,
                "product": {
                    "id": 3,
                    "name": "Another Product",
                    "description": "Another product description",
                    "price": 75.0,
                    "image": "another-product-image.jpg"
                },
                "quantity": 1,
                "unit_price": 75.0,
                "total_price": 75.0,
                "options": [3],
                "formatted_options": "Color: Red",
                "notes": "Added later"
            }
        ],
        "created_at": "2024-12-01T10:00:00.000000Z",
        "updated_at": "2024-12-01T10:05:00.000000Z"
    }
}
```

### 5. Remove Item from Order

**DELETE** `/orders/{order_id}/items`

Remove an item from an existing order (only works for pending orders).

#### Path Parameters

| Parameter  | Type    | Required | Description         |
| ---------- | ------- | -------- | ------------------- |
| `order_id` | integer | Yes      | The ID of the order |

#### Request Body

```json
{
    "item_id": 1
}
```

#### Validation Rules

| Field     | Type    | Required | Validation                                                  |
| --------- | ------- | -------- | ----------------------------------------------------------- |
| `item_id` | integer | Yes      | Required, exists in order_items table, belongs to the order |

#### Example Request

```bash
curl -X DELETE "http://localhost:8000/api/orders/1/items" \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "item_id": 1
  }'
```

#### Example Response

```json
{
    "success": true,
    "message": "Item removed from order successfully",
    "data": {
        "id": 1,
        "order_number": "ORD2024120001",
        "phone": "+1234567890",
        "shipping_address": "123 Main St, City, Country",
        "billing_address": "123 Main St, City, Country",
        "notes": "Please deliver in the morning",
        "status": "pending",
        "subtotal": 75.0,
        "tax": 11.25,
        "total": 86.25,
        "items_count": 1,
        "items": [
            {
                "id": 2,
                "product_id": 3,
                "product": {
                    "id": 3,
                    "name": "Another Product",
                    "description": "Another product description",
                    "price": 75.0,
                    "image": "another-product-image.jpg"
                },
                "quantity": 1,
                "unit_price": 75.0,
                "total_price": 75.0,
                "options": [3],
                "formatted_options": "Color: Red",
                "notes": "Added later"
            }
        ],
        "created_at": "2024-12-01T10:00:00.000000Z",
        "updated_at": "2024-12-01T10:10:00.000000Z"
    }
}
```

### 6. Delete Order

**DELETE** `/orders/{order_id}`

Delete an order (only works for pending orders).

#### Path Parameters

| Parameter  | Type    | Required | Description         |
| ---------- | ------- | -------- | ------------------- |
| `order_id` | integer | Yes      | The ID of the order |

#### Example Request

```bash
curl -X DELETE "http://localhost:8000/api/orders/1" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Order deleted successfully"
}
```

## Error Responses

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "success": false,
    "message": "Unauthorized access to order"
}
```

### 404 Not Found

```json
{
    "success": false,
    "message": "Order not found"
}
```

### 422 Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "phone": ["Phone number is required."],
        "items": ["At least one item is required."]
    }
}
```

### 500 Internal Server Error

```json
{
    "success": false,
    "message": "Failed to create order",
    "error": "Error details"
}
```

## Order Statuses

-   `pending` - Order is created but not yet confirmed
-   `confirmed` - Order has been confirmed
-   `processing` - Order is being processed
-   `shipped` - Order has been shipped
-   `delivered` - Order has been delivered
-   `cancelled` - Order has been cancelled

## Business Rules

1. **Order Modification**: Orders can only be modified (add/remove items) when status is `pending`
2. **Order Deletion**: Orders can only be deleted when status is `pending`
3. **Tax Calculation**: 15% VAT is automatically applied to all orders
4. **Order Number**: Unique order numbers are automatically generated in format `ORD{YYYY}{MM}{XXXX}`
5. **Price Calculation**: Product prices include base price plus any option price adjustments

## Integration with Appointments

Orders can be linked to appointments. When creating an appointment, you can reference an existing order ID. The appointment will be associated with that order, allowing designers to see the order details when managing appointments.

## Testing

Use the provided `test_orders_api.php` file to test all endpoints. Make sure to:

1. Replace `YOUR_AUTH_TOKEN_HERE` with a valid authentication token
2. Replace placeholder product IDs and option IDs with actual values from your database
3. Adjust the base URL if your server is running on a different port

## Rate Limiting

API requests are subject to rate limiting. Please implement appropriate retry logic in your applications.
