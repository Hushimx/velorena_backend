# Qaads Backend API Documentation

## Overview

This document describes the API endpoints for the Qaads printing company backend, including:

-   **Products and Categories API** - Managing products, categories, and product options
-   **Design System API** - Browsing, managing, and integrating designs
-   **Cart API** - Managing shopping cart with design attachments
-   **Appointments API** - Managing appointments and bookings
-   **Orders API** - Managing orders and order items
-   **Authentication API** - User authentication and management

## Design System API

The Design System API provides comprehensive functionality for managing designs, including:

-   **Public Design Browsing** - Search and browse designs without authentication
-   **User Design Management** - Favorites, collections, and personal design organization
-   **Design Integration** - Link designs to appointments and orders
-   **External API Integration** - Sync designs from external sources

For detailed Design System API documentation, see:

-   [Design System API Documentation](DESIGN_SYSTEM_API_DOCUMENTATION.md)
-   [Design API Endpoints Summary](DESIGN_API_ENDPOINTS_SUMMARY.md)

## Cart API

The Cart API provides functionality for managing shopping cart items with design attachments, including:

-   **Cart Item Management** - View cart items with attached designs
-   **Design Attachment** - Add/remove designs to specific products in cart
-   **Design Notes** - Update notes and priorities for attached designs
-   **Order Creation** - Create orders from cart with design attachments preserved

For detailed Cart API documentation, see:

-   [Cart API Documentation](CART_API_DOCUMENTATION.md)
-   [Cart API Endpoints Summary](CART_API_ENDPOINTS_SUMMARY.md)

## Products and Categories API

This section describes the API endpoints for managing products, categories, and product options for the printing company.

## Base URL

```
http://localhost:8000/api
```

## Authentication

Most endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your_token}
```

---

## Categories API

### Get All Categories

**GET** `/categories`

**Query Parameters:**

-   `is_active` (boolean, optional): Filter by active status
-   `search` (string, optional): Search by name (English or Arabic)

**Response:**

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Business Cards",
                "name_ar": "بطاقات عمل",
                "description": "Professional business cards for companies and individuals",
                "description_ar": "بطاقات عمل احترافية للشركات والأفراد",
                "image": null,
                "is_active": true,
                "sort_order": 1,
                "created_at": "2025-01-21T12:00:00.000000Z",
                "updated_at": "2025-01-21T12:00:00.000000Z"
            }
        ],
        "per_page": 15,
        "total": 5
    }
}
```

### Get Category by ID

**GET** `/categories/{id}`

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Business Cards",
        "name_ar": "بطاقات عمل",
        "description": "Professional business cards for companies and individuals",
        "description_ar": "بطاقات عمل احترافية للشركات والأفراد",
        "image": null,
        "is_active": true,
        "sort_order": 1,
        "products": [
            {
                "id": 1,
                "name": "Standard Business Cards",
                "name_ar": "بطاقات عمل قياسية",
                "base_price": "50.00"
            }
        ]
    }
}
```

### Create Category (Admin/Designer)

**POST** `/admin/categories`

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "New Category",
    "name_ar": "فئة جديدة",
    "description": "Category description",
    "description_ar": "وصف الفئة",
    "image": "path/to/image.jpg",
    "is_active": true,
    "sort_order": 1
}
```

### Update Category (Admin/Designer)

**PUT** `/admin/categories/{id}`

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "Updated Category",
    "is_active": false
}
```

### Delete Category (Admin/Designer)

**DELETE** `/admin/categories/{id}`

**Headers:**

```
Authorization: Bearer {token}
```

---

## Products API

### Get All Products

**GET** `/products`

**Query Parameters:**

-   `category_id` (integer, optional): Filter by category
-   `is_active` (boolean, optional): Filter by active status
-   `search` (string, optional): Search by name (English or Arabic)

**Examples:**

```bash
# Get all products with options
GET /api/products

# Get products in a specific category
GET /api/products?category_id=1

# Search for products
GET /api/products?search=business
```

**Response:**

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "category_id": 1,
                "name": "Standard Business Cards",
                "name_ar": "بطاقات عمل قياسية",
                "description": "Professional business cards with various customization options",
                "description_ar": "بطاقات عمل احترافية مع خيارات تخصيص متنوعة",
                "image": null,
                "base_price": "50.00",
                "is_active": true,
                "sort_order": 1,
                "specifications": null,
                "category": {
                    "id": 1,
                    "name": "Business Cards",
                    "name_ar": "بطاقات عمل"
                },
                "options": [
                    {
                        "id": 1,
                        "name": "Paper Size",
                        "name_ar": "حجم الورق",
                        "type": "select",
                        "is_required": true,
                        "values": [
                            {
                                "id": 1,
                                "value": "Standard (85x55mm)",
                                "value_ar": "قياسي (85x55مم)",
                                "price_adjustment": "0.00"
                            }
                        ]
                    }
                ]
            }
        ]
    }
}
```

### Get Product by ID

**GET** `/products/{id}`

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "category_id": 1,
        "name": "Standard Business Cards",
        "name_ar": "بطاقات عمل قياسية",
        "description": "Professional business cards with various customization options",
        "description_ar": "بطاقات عمل احترافية مع خيارات تخصيص متنوعة",
        "image": null,
        "base_price": "50.00",
        "is_active": true,
        "sort_order": 1,
        "specifications": null,
        "category": {
            "id": 1,
            "name": "Business Cards",
            "name_ar": "بطاقات عمل"
        },
        "options": [
            {
                "id": 1,
                "name": "Paper Size",
                "name_ar": "حجم الورق",
                "type": "select",
                "is_required": true,
                "values": [
                    {
                        "id": 1,
                        "value": "Standard (85x55mm)",
                        "value_ar": "قياسي (85x55مم)",
                        "price_adjustment": "0.00"
                    }
                ]
            }
        ]
    }
}
```

### Create Product (Admin/Designer)

**POST** `/admin/products`

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "category_id": 1,
    "name": "New Product",
    "name_ar": "منتج جديد",
    "description": "Product description",
    "description_ar": "وصف المنتج",
    "image": "path/to/image.jpg",
    "base_price": 100.0,
    "is_active": true,
    "sort_order": 1,
    "specifications": {
        "dimensions": "100x200mm",
        "weight": "50g"
    }
}
```

### Update Product (Admin/Designer)

**PUT** `/admin/products/{id}`

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "Updated Product",
    "base_price": 150.0
}
```

### Delete Product (Admin/Designer)

**DELETE** `/admin/products/{id}`

**Headers:**

```
Authorization: Bearer {token}
```

---

---

## Option Types

The system supports the following option types:

1. **select** - Dropdown selection (single choice)
2. **radio** - Radio button selection (single choice)
3. **checkbox** - Checkbox selection (multiple choices)
4. **text** - Text input field
5. **number** - Numeric input field

---

## API Design Best Practices

### Simple and Clean Approach

**✅ Current Implementation:**

-   **Always include options** - Products always come with their customization options
-   **Single API call** - No need for additional requests
-   **Better user experience** - All data available immediately
-   **Simpler frontend integration** - No complex state management

### Why This Works for Printing Industry

1. **Essential Data** - Options are crucial for product selection
2. **Manageable Size** - Printing products typically have 3-10 options
3. **Better UX** - Users see all customization choices immediately
4. **Simpler Code** - No need to manage separate API calls

### Response Structure

**Product with options:** ~2-5KB (depending on number of options)

**Perfect for:**

-   Product detail pages
-   Product catalogs
-   Mobile applications
-   Admin dashboards

---

## Sample Data

The system comes with pre-seeded data including:

### Categories:

-   Business Cards (بطاقات عمل)
-   Brochures & Catalogs (كتيبات وكتالوجات)
-   Banners & Signs (لافتات وعلامات)
-   Stickers & Labels (ملصقات وتسميات)
-   Packaging (تغليف)

### Products:

-   Standard Business Cards with options for paper size, type, and finish
-   Tri-Fold Brochures with options for size, paper type, and quantity
-   Roll-Up Banners with options for size, material, and stand

---

## Error Responses

All endpoints return consistent error responses:

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field_name": ["The field name field is required."]
    }
}
```

**Common HTTP Status Codes:**

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error

---

## Testing the API

You can test the API using tools like:

-   Postman
-   Insomnia
-   cURL
-   Laravel's built-in API testing

### Example cURL Commands:

**Get all categories:**

```bash
curl -X GET "http://localhost:8000/api/categories"
```

**Get all products (with options):**

```bash
curl -X GET "http://localhost:8000/api/products"
```

**Get products in a category:**

```bash
curl -X GET "http://localhost:8000/api/products?category_id=1"
```

**Get a specific product (with options):**

```bash
curl -X GET "http://localhost:8000/api/products/1"
```

**Create a new category (requires authentication):**

```bash
curl -X POST "http://localhost:8000/api/admin/categories" \
  -H "Authorization: Bearer {your_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Category",
    "name_ar": "فئة تجريبية",
    "description": "Test category description"
  }'
```
