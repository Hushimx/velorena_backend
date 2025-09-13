# Frontend API Documentation

## Overview
This document provides comprehensive information for frontend engineers about the Design System and Product Search API endpoints.

## Base URL
```
https://your-api-domain.com/api
```

## Authentication
- **Design endpoints**: Require authentication (Bearer token)
- **Product endpoints**: Public (no authentication required)
- **Design categories**: Public (no authentication required)

---

## 🎨 DESIGN SYSTEM ENDPOINTS

### Authentication Required
All design endpoints require a valid Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

### 1. Search Designs (Freepik API Integration)
**Endpoint:** `GET /api/designs/search`

**Description:** Search for designs using the Freepik API with advanced filtering options.

**Query Parameters:**
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `q` | string | ✅ | Search query (min 2 chars) | "logo design" |
| `limit` | integer | ❌ | Results per page (1-100, default: 50) | 20 |
| `page` | integer | ❌ | Page number (default: 1) | 2 |
| `category` | string | ❌ | Filter by category | "business" |
| `type` | string | ❌ | Design type: photo, vector, psd, ai | "vector" |
| `orientation` | string | ❌ | Orientation: horizontal, vertical, square | "horizontal" |
| `color` | string | ❌ | Hex color filter | "#FF0000" |
| `min_width` | integer | ❌ | Minimum width in pixels | 1920 |
| `min_height` | integer | ❌ | Minimum height in pixels | 1080 |

**Example Request:**
```javascript
const response = await fetch('/api/designs/search?q=logo&limit=20&type=vector', {
  headers: {
    'Authorization': 'Bearer your-token',
    'Content-Type': 'application/json'
  }
});
```

**Response:**
```json
{
  "success": true,
  "query": "logo",
  "data": [
    {
      "id": "12345",
      "title": "Modern Logo Design",
      "url": "https://example.com/design.jpg",
      "preview_url": "https://example.com/preview.jpg",
      "type": "vector",
      "category": "business",
      "width": 1920,
      "height": 1080,
      "downloads": 1500,
      "premium": true
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "total_results": 500,
    "per_page": 20
  },
  "filters_applied": {
    "type": "vector"
  }
}
```

### 2. Get User's Saved Designs
**Endpoint:** `GET /api/designs/saved`

**Description:** Retrieve all designs saved by the authenticated user.

**Query Parameters:**
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `per_page` | integer | ❌ | Results per page (default: 20) | 10 |

**Example Request:**
```javascript
const response = await fetch('/api/designs/saved?per_page=10', {
  headers: {
    'Authorization': 'Bearer your-token'
  }
});
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 123,
      "design_id": 456,
      "notes": "Great logo for my business",
      "created_at": "2025-01-15T10:30:00Z",
      "design": {
        "id": 456,
        "title": "Business Logo",
        "url": "https://example.com/design.jpg",
        "category": "business"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 10,
    "total": 25
  }
}
```

### 3. Save Design to Favorites
**Endpoint:** `POST /api/designs/save`

**Description:** Save a design to user's favorites.

**Request Body:**
```json
{
  "design_id": 456,
  "notes": "Optional notes about this design"
}
```

**Example Request:**
```javascript
const response = await fetch('/api/designs/save', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer your-token',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    design_id: 456,
    notes: "Perfect for my brand"
  })
});
```

**Response:**
```json
{
  "success": true,
  "message": "Design added to favorites",
  "data": {
    "id": 1,
    "user_id": 123,
    "design_id": 456,
    "notes": "Perfect for my brand",
    "created_at": "2025-01-15T10:30:00Z"
  }
}
```

### 4. Edit Favorite Design
**Endpoint:** `PUT /api/designs/favorite/{design_id}`

**Description:** Replace a favorite design with a new one.

**URL Parameters:**
- `design_id`: ID of the design to replace

**Request Body:**
```json
{
  "new_design_id": 789,
  "notes": "Updated with better design"
}
```

**Example Request:**
```javascript
const response = await fetch('/api/designs/favorite/456', {
  method: 'PUT',
  headers: {
    'Authorization': 'Bearer your-token',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    new_design_id: 789,
    notes: "Updated with better design"
  })
});
```

**Response:**
```json
{
  "success": true,
  "message": "Favorite design updated successfully",
  "data": {
    "id": 1,
    "user_id": 123,
    "design_id": 789,
    "notes": "Updated with better design",
    "design": {
      "id": 789,
      "title": "New Business Logo",
      "url": "https://example.com/new-design.jpg"
    }
  }
}
```

### 5. Remove Design from Favorites
**Endpoint:** `DELETE /api/designs/favorite/{design_id}`

**Description:** Remove a design from user's favorites.

**URL Parameters:**
- `design_id`: ID of the design to remove

**Example Request:**
```javascript
const response = await fetch('/api/designs/favorite/456', {
  method: 'DELETE',
  headers: {
    'Authorization': 'Bearer your-token'
  }
});
```

**Response:**
```json
{
  "success": true,
  "message": "Design removed from favorites"
}
```

### 6. Get Design Details
**Endpoint:** `GET /api/designs/{design_id}`

**Description:** Get detailed information about a specific design.

**URL Parameters:**
- `design_id`: ID of the design

**Example Request:**
```javascript
const response = await fetch('/api/designs/456', {
  headers: {
    'Authorization': 'Bearer your-token'
  }
});
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 456,
    "title": "Business Logo Design",
    "description": "Professional logo design for businesses",
    "url": "https://example.com/design.jpg",
    "preview_url": "https://example.com/preview.jpg",
    "category": "business",
    "type": "vector",
    "width": 1920,
    "height": 1080,
    "downloads": 1500,
    "premium": true,
    "tags": ["logo", "business", "professional"],
    "created_at": "2025-01-10T08:00:00Z"
  }
}
```

### 7. Get Design Categories (Public)
**Endpoint:** `GET /api/designs/categories`

**Description:** Get available design categories (no authentication required).

**Example Request:**
```javascript
const response = await fetch('/api/designs/categories');
```

**Response:**
```json
{
  "success": true,
  "data": [
    "business",
    "technology",
    "healthcare",
    "education",
    "food",
    "travel"
  ]
}
```

---

## 🛍️ PRODUCT SEARCH ENDPOINTS

### No Authentication Required
All product endpoints are public and don't require authentication.

### 1. Get All Products
**Endpoint:** `GET /api/products`

**Description:** Get paginated list of all active products with optional filtering.

**Query Parameters:**
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `category_id` | integer | ❌ | Filter by category ID | 1 |
| `search` | string | ❌ | Search by name (English/Arabic) | "business" |
| `page` | integer | ❌ | Page number (default: 1) | 2 |
| `limit` | integer | ❌ | Results per page (1-100, default: 15) | 20 |

**Example Request:**
```javascript
const response = await fetch('/api/products?category_id=1&search=business&limit=20');
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
        "image": "https://example.com/card.jpg",
        "base_price": "50.00",
        "is_active": true,
        "sort_order": 1,
        "specifications": "85x55mm, 300gsm",
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
    ],
    "per_page": 20,
    "total": 50,
    "last_page": 3
  }
}
```

### 2. Advanced Product Search
**Endpoint:** `GET /api/products/search`

**Description:** Advanced product search with multiple filters and sorting options.

**Query Parameters:**
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `q` | string | ✅ | Search query (min 2 chars) | "business card" |
| `category_id` | integer | ❌ | Filter by category ID | 1 |
| `min_price` | number | ❌ | Minimum price filter | 10.00 |
| `max_price` | number | ❌ | Maximum price filter | 100.00 |
| `sort_by` | string | ❌ | Sort field: name, price, created_at, sort_order | "price" |
| `sort_order` | string | ❌ | Sort direction: asc, desc | "asc" |
| `page` | integer | ❌ | Page number (default: 1) | 2 |
| `limit` | integer | ❌ | Results per page (1-100, default: 15) | 20 |

**Example Request:**
```javascript
const response = await fetch('/api/products/search?q=business%20card&min_price=20&max_price=80&sort_by=price&sort_order=asc');
```

**Response:**
```json
{
  "success": true,
  "query": "business card",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Standard Business Cards",
        "name_ar": "بطاقات عمل قياسية",
        "description": "Professional business cards",
        "base_price": "50.00",
        "image": "https://example.com/card.jpg",
        "category": {
          "id": 1,
          "name": "Business Cards",
          "name_ar": "بطاقات عمل"
        }
      }
    ],
    "per_page": 15,
    "total": 5,
    "last_page": 1
  }
}
```

### 3. Get Single Product
**Endpoint:** `GET /api/products/{product_id}`

**Description:** Get detailed information about a specific product.

**URL Parameters:**
- `product_id`: ID of the product

**Example Request:**
```javascript
const response = await fetch('/api/products/1');
```

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
    "image": "https://example.com/card.jpg",
    "base_price": "50.00",
    "is_active": true,
    "sort_order": 1,
    "specifications": "85x55mm, 300gsm",
    "created_at": "2025-01-10T08:00:00Z",
    "updated_at": "2025-01-15T10:30:00Z",
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
          },
          {
            "id": 2,
            "value": "Large (90x50mm)",
            "value_ar": "كبير (90x50مم)",
            "price_adjustment": "5.00"
          }
        ]
      }
    ]
  }
}
```

---

## 🚨 Error Handling

### Common Error Responses

**401 Unauthorized (Design endpoints only):**
```json
{
  "message": "Unauthenticated."
}
```

**422 Validation Error:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "q": ["The q field is required."],
    "design_id": ["The design id field is required."]
  }
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Design not found"
}
```

**500 Server Error:**
```json
{
  "success": false,
  "message": "Failed to fetch designs from external API"
}
```

---

## 💡 Frontend Implementation Tips

### 1. Authentication
```javascript
// Store token in localStorage or secure storage
const token = localStorage.getItem('auth_token');

// Include in all design API requests
const headers = {
  'Authorization': `Bearer ${token}`,
  'Content-Type': 'application/json'
};
```

### 2. Error Handling
```javascript
const handleApiError = (error) => {
  if (error.status === 401) {
    // Redirect to login
    window.location.href = '/login';
  } else if (error.status === 422) {
    // Show validation errors
    console.error('Validation errors:', error.errors);
  } else {
    // Show generic error message
    console.error('API Error:', error.message);
  }
};
```

### 3. Loading States
```javascript
const [loading, setLoading] = useState(false);
const [data, setData] = useState(null);

const fetchDesigns = async (query) => {
  setLoading(true);
  try {
    const response = await fetch(`/api/designs/search?q=${query}`);
    const result = await response.json();
    setData(result.data);
  } catch (error) {
    handleApiError(error);
  } finally {
    setLoading(false);
  }
};
```

### 4. Pagination
```javascript
const [currentPage, setCurrentPage] = useState(1);
const [totalPages, setTotalPages] = useState(1);

const loadPage = (page) => {
  setCurrentPage(page);
  fetchDesigns(query, page);
};
```

---

## 🔧 Testing Endpoints

### Test Design Search
```bash
curl -X GET "https://your-api.com/api/designs/search?q=logo&limit=5" \
  -H "Authorization: Bearer your-token"
```

### Test Product Search
```bash
curl -X GET "https://your-api.com/api/products/search?q=business&min_price=10&max_price=100"
```

### Test Save Design
```bash
curl -X POST "https://your-api.com/api/designs/save" \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -d '{"design_id": 123, "notes": "Test design"}'
```

---

## 📱 Mobile Considerations

1. **Image Loading**: Use lazy loading for design previews
2. **Pagination**: Implement infinite scroll for better UX
3. **Search Debouncing**: Debounce search input to avoid excessive API calls
4. **Offline Handling**: Cache frequently accessed data
5. **Error States**: Show appropriate error messages for network issues

---

## 🎯 Best Practices

1. **Rate Limiting**: Be mindful of API rate limits
2. **Caching**: Cache design categories and product lists
3. **Search Optimization**: Use debounced search with minimum 2 characters
4. **Image Optimization**: Use appropriate image sizes for different screen densities
5. **Error Recovery**: Implement retry mechanisms for failed requests
6. **Loading States**: Always show loading indicators during API calls
7. **Empty States**: Handle empty search results gracefully

---

## 📞 Support

For any questions or issues with the API endpoints, please contact the backend team or refer to the Swagger documentation at `/api/documentation`.
