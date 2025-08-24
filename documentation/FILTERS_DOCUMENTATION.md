# API Filters Documentation

## Categories Filters (`GET /api/categories`)

### Available Query Parameters:

#### `is_active` (boolean)
- **Description**: Filter by active status
- **Usage**: Use `true` to show only active categories, `false` to show only inactive categories
- **Example**: `?is_active=true`
- **Default**: Shows all categories (both active and inactive)

#### `search` (string)
- **Description**: Search categories by name (English or Arabic)
- **Usage**: Searches both `name` and `name_ar` fields
- **Example**: `?search=business`
- **Example**: `?search=بطاقات` (Arabic search)

#### `page` (integer)
- **Description**: Page number for pagination
- **Usage**: Navigate through paginated results
- **Example**: `?page=1`
- **Default**: 1

### Example API Calls:

```bash
# Get all active categories
GET /api/categories?is_active=true

# Search for business-related categories
GET /api/categories?search=business

# Get inactive categories on page 2
GET /api/categories?is_active=false&page=2

# Search Arabic categories
GET /api/categories?search=بطاقات

# Combine filters
GET /api/categories?is_active=true&search=business&page=1
```

### Response Format:
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
        "created_at": "2025-08-24T01:43:15.000000Z",
        "updated_at": "2025-08-24T01:43:15.000000Z"
      }
    ],
    "per_page": 15,
    "total": 5,
    "last_page": 1,
    "from": 1,
    "to": 5
  }
}
```

---

## Products Filters (`GET /api/products`)

### Available Query Parameters:

#### `category_id` (integer)
- **Description**: Filter products by category ID
- **Usage**: Use this to get products from a specific category
- **Example**: `?category_id=1`
- **Default**: Shows products from all categories

#### `is_active` (boolean)
- **Description**: Filter by active status
- **Usage**: Use `true` to show only active products, `false` to show only inactive products
- **Example**: `?is_active=true`
- **Default**: Shows all products (both active and inactive)

#### `search` (string)
- **Description**: Search products by name (English or Arabic)
- **Usage**: Searches both `name` and `name_ar` fields
- **Example**: `?search=business`
- **Example**: `?search=بطاقات` (Arabic search)

#### `page` (integer)
- **Description**: Page number for pagination
- **Usage**: Navigate through paginated results
- **Example**: `?page=1`
- **Default**: 1

### Example API Calls:

```bash
# Get all products from category ID 1
GET /api/products?category_id=1

# Get active products from category 1
GET /api/products?category_id=1&is_active=true

# Search for business card products
GET /api/products?search=business

# Get products from category 2, page 1
GET /api/products?category_id=2&page=1

# Search Arabic products
GET /api/products?search=بطاقات

# Combine multiple filters
GET /api/products?category_id=1&is_active=true&search=business&page=1
```

### Response Format:
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
    ],
    "per_page": 15,
    "total": 30,
    "last_page": 2,
    "from": 1,
    "to": 15
  }
}
```

---

## Filter Usage Tips

### 1. **Combining Filters**
You can combine multiple filters in a single request:
```bash
GET /api/products?category_id=1&is_active=true&search=business&page=1
```

### 2. **Bilingual Search**
Both endpoints support bilingual search:
- **English**: `?search=business`
- **Arabic**: `?search=بطاقات`

### 3. **Pagination**
- Default page size: 15 items per page
- Use `page` parameter to navigate through results
- Response includes pagination metadata (`current_page`, `total`, `last_page`, etc.)

### 4. **Active Status Filtering**
- `is_active=true`: Show only active items
- `is_active=false`: Show only inactive items
- No `is_active` parameter: Show all items

### 5. **Category Filtering (Products Only)**
- Use `category_id` to filter products by specific category
- Useful for building category-specific product listings

---

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Invalid filter parameters"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "No results found"
}
```

---

## Rate Limiting
- Standard API rate limits apply
- Consider caching frequently used filtered results
- Use pagination for large result sets

