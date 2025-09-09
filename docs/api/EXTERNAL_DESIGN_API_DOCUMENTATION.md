# External Design API Documentation

This document describes the protected API endpoints that allow frontend applications to access external design services (FreeAPI) without exposing the API key.

## Overview

The External Design API provides secure access to external design resources through your Laravel backend. The API key is protected on the server side, and the frontend only needs to make requests to your endpoints.

## Base URL

```
/api/external/designs
```

## Authentication

These endpoints are **public** (no authentication required) but the external API key is protected on the server side.

## Endpoints

### 1. Search Designs

Search for designs using keywords and various filters.

**Endpoint:** `GET /api/external/designs/search`

**Parameters:**

-   `q` (required): Search query string (minimum 2 characters)
-   `limit` (optional): Number of results per page (1-100, default: 50)
-   `page` (optional): Page number (minimum 1, default: 1)
-   `category` (optional): Filter by category
-   `type` (optional): Filter by type (`photo`, `vector`, `psd`, `ai`)
-   `orientation` (optional): Filter by orientation (`horizontal`, `vertical`, `square`)
-   `color` (optional): Filter by color (hex format: `#RRGGBB`)
-   `min_width` (optional): Minimum width in pixels
-   `min_height` (optional): Minimum height in pixels

**Example Request:**

```bash
GET /api/external/designs/search?q=business&limit=20&type=vector&orientation=horizontal
```

**Example Response:**

```json
{
    "success": true,
    "query": "business",
    "data": [
        {
            "id": "12345",
            "title": "Business Card Design",
            "description": "Modern business card template",
            "image_url": "https://example.com/image.jpg",
            "thumbnail_url": "https://example.com/thumb.jpg",
            "category": "business",
            "type": "vector",
            "orientation": "horizontal",
            "width": 800,
            "height": 600,
            "tags": ["business", "card", "template"]
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 10,
        "total_results": 500,
        "per_page": 20
    },
    "filters_applied": {
        "type": "vector",
        "orientation": "horizontal"
    }
}
```

### 2. Get Designs by Category

Retrieve designs from a specific category.

**Endpoint:** `GET /api/external/designs/category`

**Parameters:**

-   `category` (required): Category name
-   `limit` (optional): Number of results per page (1-100, default: 50)
-   `page` (optional): Page number (minimum 1, default: 1)
-   `type` (optional): Filter by type (`photo`, `vector`, `psd`, `ai`)
-   `orientation` (optional): Filter by orientation (`horizontal`, `vertical`, `square`)
-   `color` (optional): Filter by color (hex format: `#RRGGBB`)
-   `min_width` (optional): Minimum width in pixels
-   `min_height` (optional): Minimum height in pixels

**Example Request:**

```bash
GET /api/external/designs/category?category=technology&limit=30&type=photo
```

**Example Response:**

```json
{
    "success": true,
    "category": "technology",
    "data": [
        {
            "id": "67890",
            "title": "Tech Background",
            "description": "Modern technology background",
            "image_url": "https://example.com/tech-bg.jpg",
            "thumbnail_url": "https://example.com/tech-thumb.jpg",
            "category": "technology",
            "type": "photo",
            "orientation": "horizontal",
            "width": 1920,
            "height": 1080,
            "tags": ["technology", "background", "modern"]
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 5,
        "total_results": 150,
        "per_page": 30
    },
    "filters_applied": {
        "type": "photo"
    }
}
```

### 3. Get Available Categories

Retrieve all available design categories from the external API.

**Endpoint:** `GET /api/external/designs/categories`

**Parameters:** None

**Example Request:**

```bash
GET /api/external/designs/categories
```

**Example Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": "business",
            "name": "Business",
            "description": "Business-related designs",
            "count": 1250
        },
        {
            "id": "technology",
            "name": "Technology",
            "description": "Technology and digital designs",
            "count": 890
        },
        {
            "id": "nature",
            "name": "Nature",
            "description": "Nature and outdoor designs",
            "count": 2100
        }
    ],
    "total_categories": 25
}
```

### 4. Get Featured Designs

Retrieve featured or popular designs from the external API.

**Endpoint:** `GET /api/external/designs/featured`

**Parameters:**

-   `limit` (optional): Number of results per page (1-100, default: 50)
-   `page` (optional): Page number (minimum 1, default: 1)
-   `type` (optional): Filter by type (`photo`, `vector`, `psd`, `ai`)
-   `orientation` (optional): Filter by orientation (`horizontal`, `vertical`, `square`)

**Example Request:**

```bash
GET /api/external/designs/featured?limit=20&type=vector
```

**Example Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": "featured-001",
            "title": "Popular Logo Design",
            "description": "Trending logo design template",
            "image_url": "https://example.com/logo.jpg",
            "thumbnail_url": "https://example.com/logo-thumb.jpg",
            "category": "business",
            "type": "vector",
            "orientation": "square",
            "width": 500,
            "height": 500,
            "tags": ["logo", "business", "trending"],
            "featured": true
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 3,
        "total_results": 60,
        "per_page": 20
    }
}
```

## Error Responses

All endpoints return consistent error responses:

**400 Bad Request:**

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "q": ["The q field is required."],
        "limit": ["The limit must be between 1 and 100."]
    }
}
```

**500 Internal Server Error:**

```json
{
    "success": false,
    "message": "Failed to fetch designs from external API"
}
```

## Rate Limiting

The external API calls are rate-limited by the external service. If you encounter rate limiting issues, consider implementing caching or request queuing.

## Caching Recommendations

For better performance, consider implementing caching for:

-   Category lists (cache for 1 hour)
-   Featured designs (cache for 30 minutes)
-   Search results (cache for 15 minutes)

## Frontend Integration Examples

### JavaScript/Fetch API

```javascript
// Search for designs
async function searchDesigns(query, filters = {}) {
    const params = new URLSearchParams({
        q: query,
        ...filters,
    });

    const response = await fetch(`/api/external/designs/search?${params}`);
    const data = await response.json();

    if (data.success) {
        return data.data;
    } else {
        throw new Error(data.message);
    }
}

// Get designs by category
async function getDesignsByCategory(category, filters = {}) {
    const params = new URLSearchParams({
        category: category,
        ...filters,
    });

    const response = await fetch(`/api/external/designs/category?${params}`);
    const data = await response.json();

    if (data.success) {
        return data.data;
    } else {
        throw new Error(data.message);
    }
}

// Get categories
async function getCategories() {
    const response = await fetch("/api/external/designs/categories");
    const data = await response.json();

    if (data.success) {
        return data.data;
    } else {
        throw new Error(data.message);
    }
}
```

### Axios Example

```javascript
import axios from "axios";

const api = axios.create({
    baseURL: "/api/external/designs",
});

// Search designs
export const searchDesigns = (query, filters = {}) => {
    return api.get("/search", {
        params: { q: query, ...filters },
    });
};

// Get designs by category
export const getDesignsByCategory = (category, filters = {}) => {
    return api.get("/category", {
        params: { category, ...filters },
    });
};

// Get categories
export const getCategories = () => {
    return api.get("/categories");
};

// Get featured designs
export const getFeaturedDesigns = (filters = {}) => {
    return api.get("/featured", {
        params: filters,
    });
};
```

## Security Notes

1. **API Key Protection**: The external API key is stored securely on the server and never exposed to the frontend.
2. **Input Validation**: All input parameters are validated to prevent injection attacks.
3. **Rate Limiting**: Consider implementing rate limiting on your endpoints to prevent abuse.
4. **CORS**: Configure CORS properly if your frontend is on a different domain.

## Environment Configuration

Add these environment variables to your `.env` file:

```env
FREEPIK_API_KEY=your_api_key_here
FREEPIK_BASE_URL=https://api.freepik.com/v1
```

## Testing

You can test the endpoints using tools like Postman or curl:

```bash
# Search for designs
curl "http://your-domain.com/api/external/designs/search?q=business&limit=10"

# Get designs by category
curl "http://your-domain.com/api/external/designs/category?category=technology&limit=10"

# Get categories
curl "http://your-domain.com/api/external/designs/categories"

# Get featured designs
curl "http://your-domain.com/api/external/designs/featured?limit=10"
```
