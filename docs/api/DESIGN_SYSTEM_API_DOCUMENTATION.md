# Design System API Documentation

This document provides comprehensive documentation for the Design System API endpoints in the Velorena application.

## Table of Contents

1. [Overview](#overview)
2. [Public Design Routes](#public-design-routes)
3. [User Design Management Routes](#user-design-management-routes)
4. [Authentication](#authentication)
5. [Response Format](#response-format)
6. [Error Handling](#error-handling)
7. [Examples](#examples)

## Overview

The Design System API provides endpoints for:

-   Browsing and searching designs
-   Managing user favorites and collections
-   Linking designs to appointments and orders
-   Syncing designs from external APIs

## Public Design Routes

These routes are accessible without authentication.

### 1. List All Designs

**GET** `/api/designs`

Retrieve a paginated list of all active designs with optional filtering.

#### Query Parameters

| Parameter  | Type    | Required | Description                                      |
| ---------- | ------- | -------- | ------------------------------------------------ |
| `category` | string  | No       | Filter by design category                        |
| `search`   | string  | No       | Search in title, description, and tags           |
| `per_page` | integer | No       | Number of items per page (default: 20, max: 100) |

#### Response

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "external_id": "ext_123",
      "title": "Modern Business Card",
      "description": "Clean and professional business card design",
      "image_url": "https://example.com/image.jpg",
      "thumbnail_url": "https://example.com/thumb.jpg",
      "category": "Business",
      "tags": "business,card,professional",
      "metadata": {...},
      "is_active": true,
      "created_at": "2025-01-27T10:00:00.000000Z",
      "updated_at": "2025-01-27T10:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100
  }
}
```

### 2. Search Designs

**GET** `/api/designs/search`

Search designs by query with optional category filtering.

#### Query Parameters

| Parameter  | Type    | Required | Description                                      |
| ---------- | ------- | -------- | ------------------------------------------------ |
| `q`        | string  | Yes      | Search query (minimum 2 characters)              |
| `category` | string  | No       | Filter by design category                        |
| `per_page` | integer | No       | Number of items per page (default: 20, max: 100) |

#### Response

```json
{
  "success": true,
  "query": "business card",
  "data": [...],
  "pagination": {...}
}
```

### 3. Get Design Categories

**GET** `/api/designs/categories`

Retrieve all available design categories.

#### Response

```json
{
    "success": true,
    "data": ["Business", "Creative", "Minimalist", "Vintage"]
}
```

### 4. Get Design Details

**GET** `/api/designs/{design}`

Retrieve detailed information about a specific design.

#### Response

```json
{
    "success": true,
    "data": {
        "id": 1,
        "external_id": "ext_123",
        "title": "Modern Business Card",
        "description": "Clean and professional business card design",
        "image_url": "https://example.com/image.jpg",
        "thumbnail_url": "https://example.com/thumb.jpg",
        "category": "Business",
        "tags": "business,card,professional",
        "metadata": {
            "author": "John Doe",
            "license": "commercial",
            "tags": ["business", "card", "professional"]
        },
        "is_active": true,
        "created_at": "2025-01-27T10:00:00.000000Z",
        "updated_at": "2025-01-27T10:00:00.000000Z"
    }
}
```

### 5. Sync Designs from External API

**POST** `/api/designs/sync`

Sync designs from external API to local database.

#### Request Body

```json
{
    "limit": 100,
    "category": "Business",
    "search": "logo"
}
```

#### Response

```json
{
    "success": true,
    "message": "Designs synced successfully",
    "synced_count": 25
}
```

## User Design Management Routes

These routes require authentication using Laravel Sanctum.

### Authentication

All user management routes require a valid Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

### 1. Design Favorites

#### Add Design to Favorites

**POST** `/api/designs/{design}/favorite`

Add a design to the authenticated user's favorites.

#### Request Body

```json
{
    "notes": "Love this design for my business card"
}
```

#### Response

```json
{
    "success": true,
    "message": "Design added to favorites",
    "data": {
        "id": 1,
        "user_id": 1,
        "design_id": 5,
        "notes": "Love this design for my business card",
        "created_at": "2025-01-27T10:00:00.000000Z",
        "updated_at": "2025-01-27T10:00:00.000000Z"
    }
}
```

#### Remove Design from Favorites

**DELETE** `/api/designs/{design}/favorite`

Remove a design from the authenticated user's favorites.

#### Response

```json
{
    "success": true,
    "message": "Design removed from favorites"
}
```

#### Get User's Favorite Designs

**GET** `/api/designs/favorites`

Retrieve all designs favorited by the authenticated user.

#### Query Parameters

| Parameter  | Type    | Required | Description                            |
| ---------- | ------- | -------- | -------------------------------------- |
| `per_page` | integer | No       | Number of items per page (default: 20) |

#### Response

```json
{
  "success": true,
  "data": [...],
  "pagination": {...}
}
```

### 2. Design Collections

#### Create Collection

**POST** `/api/designs/collections`

Create a new design collection.

#### Request Body

```json
{
    "name": "My Business Designs",
    "description": "Collection of business-related designs",
    "is_public": false,
    "color": "#3B82F6"
}
```

#### Response

```json
{
    "success": true,
    "message": "Collection created successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "name": "My Business Designs",
        "description": "Collection of business-related designs",
        "is_public": false,
        "color": "#3B82F6",
        "created_at": "2025-01-27T10:00:00.000000Z",
        "updated_at": "2025-01-27T10:00:00.000000Z"
    }
}
```

#### Get User's Collections

**GET** `/api/designs/collections`

Retrieve all collections created by the authenticated user.

#### Response

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "My Business Designs",
      "description": "Collection of business-related designs",
      "is_public": false,
      "color": "#3B82F6",
      "designs_count": 5,
      "created_at": "2025-01-27T10:00:00.000000Z",
      "updated_at": "2025-01-27T10:00:00.000000Z"
    }
  ],
  "pagination": {...}
}
```

#### Get Collection Details

**GET** `/api/designs/collections/{collection}`

Retrieve detailed information about a specific collection.

#### Response

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "My Business Designs",
        "description": "Collection of business-related designs",
        "is_public": false,
        "color": "#3B82F6",
        "designs": [
            {
                "id": 1,
                "title": "Modern Business Card",
                "image_url": "https://example.com/image.jpg",
                "pivot": {
                    "notes": "Perfect for my business",
                    "added_at": "2025-01-27T10:00:00.000000Z"
                }
            }
        ],
        "created_at": "2025-01-27T10:00:00.000000Z",
        "updated_at": "2025-01-27T10:00:00.000000Z"
    }
}
```

#### Update Collection

**PUT** `/api/designs/collections/{collection}`

Update an existing collection.

#### Request Body

```json
{
    "name": "Updated Collection Name",
    "description": "Updated description",
    "is_public": true,
    "color": "#10B981"
}
```

#### Delete Collection

**DELETE** `/api/designs/collections/{collection}`

Delete a collection and all its items.

#### Add Design to Collection

**POST** `/api/designs/collections/{collection}/designs`

Add a design to a collection.

#### Request Body

```json
{
    "design_id": 5,
    "notes": "Great design for inspiration"
}
```

#### Remove Design from Collection

**DELETE** `/api/designs/collections/{collection}/designs/{design}`

Remove a design from a collection.

### 3. Design Integration with Appointments

#### Link Design to Appointment

**POST** `/api/designs/{design}/appointments/{appointment}`

Link a design to an appointment with optional notes and priority.

#### Request Body

```json
{
    "notes": "Use this design as inspiration",
    "priority": 1
}
```

#### Unlink Design from Appointment

**DELETE** `/api/designs/{design}/appointments/{appointment}`

Remove the link between a design and an appointment.

### 4. Design Integration with Orders

#### Link Design to Order

**POST** `/api/designs/{design}/orders/{order}`

Link a design to all products in an order.

#### Request Body

```json
{
    "notes": "Apply this design to all products",
    "priority": 1
}
```

#### Unlink Design from Order

**DELETE** `/api/designs/{design}/orders/{order}`

Remove the link between a design and an order.

### 5. Design History

#### Get Design History

**GET** `/api/designs/history`

Retrieve all designs the user has interacted with (favorites, collections, appointments, orders).

#### Query Parameters

| Parameter  | Type    | Required | Description                            |
| ---------- | ------- | -------- | -------------------------------------- |
| `per_page` | integer | No       | Number of items per page (default: 20) |

#### Response

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Modern Business Card",
      "image_url": "https://example.com/image.jpg",
      "category": "Business",
      "favorites": [
        {
          "id": 1,
          "user_id": 1,
          "design_id": 1,
          "notes": "Love this design",
          "created_at": "2025-01-27T10:00:00.000000Z"
        }
      ],
      "created_at": "2025-01-27T10:00:00.000000Z"
    }
  ],
  "pagination": {...}
}
```

## Response Format

All API responses follow a consistent format:

### Success Response

```json
{
  "success": true,
  "data": {...},
  "message": "Optional success message",
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100
  }
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
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Internal Server Error

## Examples

### Complete Workflow Example

1. **Search for designs:**

    ```bash
    GET /api/designs/search?q=business&category=Business
    ```

2. **Add design to favorites:**

    ```bash
    POST /api/designs/5/favorite
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "notes": "Perfect for my business card"
    }
    ```

3. **Create a collection:**

    ```bash
    POST /api/designs/collections
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "name": "Business Card Designs",
      "description": "My favorite business card designs",
      "is_public": false,
      "color": "#3B82F6"
    }
    ```

4. **Add design to collection:**

    ```bash
    POST /api/designs/collections/1/designs
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "design_id": 5,
      "notes": "Great inspiration"
    }
    ```

5. **Link design to appointment:**

    ```bash
    POST /api/designs/5/appointments/10
    Authorization: Bearer {token}
    Content-Type: application/json

    {
      "notes": "Use this design as reference",
      "priority": 1
    }
    ```

## Rate Limiting

API endpoints are rate-limited to prevent abuse. Current limits:

-   Public routes: 100 requests per minute
-   Authenticated routes: 200 requests per minute

## Support

For API support and questions, please contact the development team or refer to the main API documentation.
