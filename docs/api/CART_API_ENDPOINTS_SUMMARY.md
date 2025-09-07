# Cart API Endpoints Summary

This document provides a quick reference for all Cart API endpoints that allow users to manage their shopping cart with design attachments.

## Cart Management Endpoints (All Require Authentication)

| Method | Endpoint                              | Description                            |
| ------ | ------------------------------------- | -------------------------------------- |
| POST   | `/api/cart/items`                     | Enhance localStorage cart with designs |
| POST   | `/api/cart/designs`                   | Add design to cart item                |
| DELETE | `/api/cart/designs`                   | Remove design from cart item           |
| PUT    | `/api/cart/designs/notes`             | Update design notes in cart item       |
| GET    | `/api/cart/items/{productId}/designs` | Get designs for specific cart item     |
| POST   | `/api/cart/checkout`                  | Create order from cart with designs    |

## Quick Test Commands

### Test Cart Endpoints

```bash
# Set your token
TOKEN="your-bearer-token-here"

# Get cart items with designs (enhance localStorage cart)
curl -X POST "http://localhost:8000/api/cart/items" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 2,
        "selected_options": {"1": "2"},
        "notes": "Premium paper"
      }
    ]
  }'

# Add design to cart item
curl -X POST "http://localhost:8000/api/cart/designs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "product_id": 1,
    "design_id": 5,
    "notes": "Perfect for my business",
    "priority": 1
  }'

# Get designs for specific cart item
curl -X GET "http://localhost:8000/api/cart/items/1/designs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

# Update design notes
curl -X PUT "http://localhost:8000/api/cart/designs/notes" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "product_id": 1,
    "design_id": 5,
    "notes": "Updated notes",
    "priority": 2
  }'

# Remove design from cart item
curl -X DELETE "http://localhost:8000/api/cart/designs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "product_id": 1,
    "design_id": 5
  }'

# Create order from cart
curl -X POST "http://localhost:8000/api/cart/checkout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 2,
        "selected_options": {"1": "2"},
        "notes": "Premium paper"
      }
    ],
    "phone": "+1234567890",
    "shipping_address": "123 Main St, City, Country",
    "notes": "Handle with care"
  }'
```

## Request/Response Examples

### Add Design to Cart Item

**Request:**

```json
POST /api/cart/designs
{
  "product_id": 1,
  "design_id": 5,
  "notes": "Use this design as inspiration",
  "priority": 1
}
```

**Response:**

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

### Get Cart Items with Designs

**Request:**

```json
GET /api/cart/items
```

**Response:**

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
                    "base_price": "50.00"
                },
                "quantity": 2,
                "designs": [
                    {
                        "id": 5,
                        "title": "Modern Business Card Design",
                        "image_url": "https://example.com/design.jpg",
                        "notes": "Use this design as inspiration",
                        "priority": 1
                    }
                ],
                "unit_price": 75.0,
                "total_price": 150.0
            }
        ],
        "total": 150.0,
        "item_count": 1
    }
}
```

## Features Implemented

✅ **Cart Item Management**

-   Get all cart items with design attachments
-   View product details and pricing
-   See attached designs with notes and priorities

✅ **Design Attachment**

-   Add designs to specific products in cart
-   Remove designs from cart items
-   Update design notes and priorities
-   Get all designs for a specific cart item

✅ **Order Creation**

-   Create orders from cart with design attachments
-   Preserve all design relationships in orders
-   Clear cart after successful order creation

✅ **Integration**

-   Works with existing Design System API
-   Integrates with Order System
-   Uses Product System for pricing and options
-   Secure authentication with Laravel Sanctum

## Database Tables Used

-   **product_designs** - Stores design attachments to products
-   **products** - Product information and pricing
-   **designs** - Design information and metadata
-   **orders** - Order information
-   **order_items** - Order line items

## Error Responses

### Product Not in Cart

```json
{
    "success": false,
    "message": "Product not found in cart"
}
```

### Design Not Found

```json
{
    "success": false,
    "message": "Design not found or inactive"
}
```

### Validation Error

```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "product_id": ["The product id field is required."],
        "design_id": ["The design id field is required."]
    }
}
```

## Integration Notes

-   **localStorage Cart**: Cart items are stored in localStorage (client-side) and managed by JavaScript
-   **Design Persistence**: Design attachments are stored in database and preserved in orders
-   **Hybrid Approach**: Combines client-side cart management with server-side design persistence
-   **Authentication**: All endpoints require valid Bearer token
-   **Validation**: All inputs are validated before processing
-   **Error Handling**: Comprehensive error responses with appropriate HTTP status codes

## Next Steps

1. **Testing**: Test all endpoints with Postman or similar tools
2. **Frontend Integration**: Integrate with existing Livewire cart components
3. **Database Migration**: Consider migrating cart storage to database for persistence
4. **Performance**: Add caching for frequently accessed cart data
5. **Analytics**: Track cart abandonment and design attachment patterns
