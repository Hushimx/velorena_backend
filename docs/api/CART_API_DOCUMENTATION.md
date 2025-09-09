# Cart API Documentation

This document provides comprehensive documentation for the Cart API endpoints that allow users to manage their shopping cart with design attachments.

## Table of Contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Cart Management Endpoints](#cart-management-endpoints)
4. [Response Format](#response-format)
5. [Error Handling](#error-handling)
6. [Examples](#examples)

## Overview

The Cart API provides endpoints for:

-   Managing cart items with design attachments
-   Adding/removing designs from specific products in the cart
-   Updating design notes and priorities
-   Creating orders from cart with design attachments

## Authentication

All cart endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## Cart Management Endpoints

### 1. Get Cart Items with Designs

**GET** `/api/cart/items`

Enhance cart items from localStorage with design information. Send your cart items from localStorage to get enhanced data with design attachments.

#### Request Body

```json
{
    "cart_items": [
        {
            "product_id": 1,
            "quantity": 2,
            "selected_options": {
                "1": "2",
                "3": "5"
            },
            "notes": "Please use premium paper"
        }
    ]
}
```

#### Response

```json
{
  "success": true,
  "data": {
    "items": [
      {
        "product_id": 1,
        "product": {
          "id": 1,
          "name": "Standard Business Cards",
          "name_ar": "بطاقات عمل قياسية",
          "base_price": "50.00",
          "image": "path/to/image.jpg",
          "options": [...]
        },
        "quantity": 2,
        "selected_options": {
          "1": "2",
          "3": "5"
        },
        "notes": "Please use premium paper",
        "designs": [
          {
            "id": 5,
            "title": "Modern Business Card Design",
            "image_url": "https://example.com/design.jpg",
            "thumbnail_url": "https://example.com/thumb.jpg",
            "notes": "Use this design as inspiration",
            "priority": 1,
            "attached_at": "2025-01-27T10:00:00.000000Z"
          }
        ],
        "unit_price": 75.00,
        "total_price": 150.00
      }
    ],
    "total": 150.00,
    "item_count": 1
  }
}
```

### 2. Add Design to Cart Item

**POST** `/api/cart/designs`

Add a design to a specific product in the user's cart.

#### Request Body

```json
{
    "product_id": 1,
    "design_id": 5,
    "notes": "Use this design as inspiration",
    "priority": 1
}
```

#### Response

```json
{
    "success": true,
    "message": "Design added to cart item successfully",
    "data": {
        "product_design_id": 12,
        "design": {
            "id": 5,
            "title": "Modern Business Card Design",
            "image_url": "https://example.com/design.jpg",
            "thumbnail_url": "https://example.com/thumb.jpg"
        },
        "notes": "Use this design as inspiration",
        "priority": 1
    }
}
```

### 3. Remove Design from Cart Item

**DELETE** `/api/cart/designs`

Remove a design from a specific product in the user's cart.

#### Request Body

```json
{
    "product_id": 1,
    "design_id": 5
}
```

#### Response

```json
{
    "success": true,
    "message": "Design removed from cart item successfully"
}
```

### 4. Update Design Notes

**PUT** `/api/cart/designs/notes`

Update notes and priority for a design attached to a cart item.

#### Request Body

```json
{
    "product_id": 1,
    "design_id": 5,
    "notes": "Updated notes for this design",
    "priority": 2
}
```

#### Response

```json
{
    "success": true,
    "message": "Design notes updated successfully",
    "data": {
        "notes": "Updated notes for this design",
        "priority": 2
    }
}
```

### 5. Get Designs for Cart Item

**GET** `/api/cart/items/{productId}/designs`

Retrieve all designs attached to a specific product in the cart.

#### Response

```json
{
    "success": true,
    "data": [
        {
            "id": 5,
            "title": "Modern Business Card Design",
            "description": "Clean and professional design",
            "image_url": "https://example.com/design.jpg",
            "thumbnail_url": "https://example.com/thumb.jpg",
            "category": "Business",
            "notes": "Use this design as inspiration",
            "priority": 1,
            "attached_at": "2025-01-27T10:00:00.000000Z"
        }
    ]
}
```

### 6. Create Order from Cart

**POST** `/api/cart/checkout`

Create an order from cart items with all design attachments. Send your cart items from localStorage along with order details.

#### Request Body

```json
{
    "cart_items": [
        {
            "product_id": 1,
            "quantity": 2,
            "selected_options": {
                "1": "2",
                "3": "5"
            },
            "notes": "Please use premium paper"
        }
    ],
    "phone": "+1234567890",
    "shipping_address": "123 Main St, City, Country",
    "billing_address": "123 Main St, City, Country",
    "notes": "Please handle with care"
}
```

#### Response

```json
{
    "success": true,
    "message": "Order created successfully",
    "data": {
        "order_id": 15,
        "order_number": "ORD-2025-0015",
        "total": 150.0
    }
}
```

## Response Format

All API responses follow a consistent format:

### Success Response

```json
{
  "success": true,
  "data": {...},
  "message": "Optional success message"
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

## Error Handling

The API uses standard HTTP status codes:

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Internal Server Error

## Examples

### Complete Workflow Example

1. **Get cart items with designs:**

    ```bash
    POST /api/cart/items
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "cart_items": [
        {
          "product_id": 1,
          "quantity": 2,
          "selected_options": {"1": "2"},
          "notes": "Premium paper"
        }
      ]
    }
    ```

2. **Add design to cart item:**

    ```bash
    POST /api/cart/designs
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "product_id": 1,
      "design_id": 5,
      "notes": "Perfect for my business",
      "priority": 1
    }
    ```

3. **Get designs for specific cart item:**

    ```bash
    GET /api/cart/items/1/designs
    Authorization: Bearer {token}
    ```

4. **Update design notes:**

    ```bash
    PUT /api/cart/designs/notes
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "product_id": 1,
      "design_id": 5,
      "notes": "Updated notes",
      "priority": 2
    }
    ```

5. **Create order from cart:**

    ```bash
    POST /api/cart/checkout
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "cart_items": [
        {
          "product_id": 1,
          "quantity": 2,
          "selected_options": {"1": "2"},
          "notes": "Premium paper"
        }
      ],
      "phone": "+1234567890",
      "shipping_address": "123 Main St",
      "notes": "Handle with care"
    }
    ```

### JavaScript/Frontend Integration Example

```javascript
// Add design to cart item
async function addDesignToCart(productId, designId, notes = "", priority = 1) {
    try {
        const response = await fetch("/api/cart/designs", {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                product_id: productId,
                design_id: designId,
                notes: notes,
                priority: priority,
            }),
        });

        const data = await response.json();

        if (data.success) {
            console.log("Design added to cart:", data.data);
            // Update UI to show the design is attached
        } else {
            console.error("Error:", data.message);
        }
    } catch (error) {
        console.error("Network error:", error);
    }
}

// Get cart items with designs (enhance localStorage cart with design data)
async function getCartItemsWithDesigns() {
    try {
        // Get cart from localStorage
        const cartData = JSON.parse(
            localStorage.getItem("shopping_cart") ||
                '{"items": [], "total": 0, "itemCount": 0}'
        );

        if (cartData.items.length === 0) {
            return [];
        }

        // Send cart items to API to get enhanced data with designs
        const response = await fetch("/api/cart/items", {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                cart_items: cartData.items,
            }),
        });

        const data = await response.json();

        if (data.success) {
            return data.data.items;
        } else {
            console.error("Error:", data.message);
            return [];
        }
    } catch (error) {
        console.error("Network error:", error);
        return [];
    }
}

// Remove design from cart item
async function removeDesignFromCart(productId, designId) {
    try {
        const response = await fetch("/api/cart/designs", {
            method: "DELETE",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                product_id: productId,
                design_id: designId,
            }),
        });

        const data = await response.json();

        if (data.success) {
            console.log("Design removed from cart");
            // Update UI to remove the design
        } else {
            console.error("Error:", data.message);
        }
    } catch (error) {
        console.error("Network error:", error);
    }
}
```

## Integration with Existing Systems

The Cart API integrates seamlessly with:

-   **Design System API** - Uses the same design models and relationships
-   **Order System** - Creates orders with design attachments preserved
-   **Product System** - Works with existing product options and pricing
-   **User Authentication** - Uses Laravel Sanctum for secure access

## Notes

-   **Cart Storage**: Cart items are stored in localStorage (client-side) and managed by JavaScript
-   **Design Storage**: Design attachments are stored in the `product_designs` table (server-side)
-   **API Integration**: The API enhances localStorage cart data with design information from the database
-   **Order Creation**: All design attachments are preserved when creating orders from cart
-   **Validation**: The frontend should validate that products exist in localStorage before calling design APIs
-   **Design Priorities**: Help organize multiple designs per product
-   **Hybrid Approach**: Combines client-side cart management with server-side design persistence
