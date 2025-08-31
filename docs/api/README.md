# API Documentation

Welcome to the Velorena API documentation. This section contains all API-related documentation, guides, and best practices.

## 📋 API Documentation Index

### 🔌 Core API Documentation

-   **[API Documentation](API_DOCUMENTATION.md)** - Complete API reference and endpoints
-   **[Orders API](API_ORDERS_DOCUMENTATION.md)** - Orders API endpoints and usage
-   **[Appointments API](API_APPOINTMENTS_DOCUMENTATION.md)** - Appointments API endpoints and usage
-   **[API Best Practices](API_ORDERS_BEST_PRACTICES.md)** - Laravel best practices implementation

## 🚀 Quick Start

### Authentication

All API endpoints require authentication using Laravel Sanctum. Include your Bearer token in the Authorization header:

```bash
Authorization: Bearer {your-token}
```

### Base URL

```
http://your-domain.com/api
```

## 📚 API Categories

### 🔐 Authentication & Authorization

-   User registration and login
-   OTP verification
-   Token management
-   Profile management

### 📦 Orders Management

-   Create, read, update, delete orders
-   Add/remove items from orders
-   Order status management
-   Price calculations with tax

### 🗓️ Appointments Management

-   Create, read, update, delete appointments
-   Appointment status management (pending, confirmed, cancelled, completed)
-   Designer availability checking
-   Time slot management
-   Appointment scheduling and booking

### 🛍️ Products & Categories

-   Product listing and details
-   Category management
-   Product options and variants
-   File uploads for products

### 📄 Documents

-   Document upload and management
-   File storage and retrieval
-   Document information

## 🛠️ Development Tools

### Testing

-   Use the provided test scripts in the root directory
-   Test with Postman or cURL
-   Check examples in each documentation file

### Validation

-   Form Requests for input validation
-   Custom error messages
-   Business rule enforcement

### Response Formatting

-   API Resources for consistent responses
-   Proper HTTP status codes
-   Error handling

## 📖 Getting Started with API Development

1. **Read the main API documentation** - [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
2. **Learn about Orders API** - [API_ORDERS_DOCUMENTATION.md](API_ORDERS_DOCUMENTATION.md)
3. **Follow best practices** - [API_ORDERS_BEST_PRACTICES.md](API_ORDERS_BEST_PRACTICES.md)
4. **Use the test scripts** provided in the project root

## 🔧 API Features

### RESTful Design

-   Proper HTTP methods (GET, POST, PUT, DELETE)
-   Consistent URL structure
-   Standard HTTP status codes

### Advanced Features

-   Pagination for large datasets
-   Filtering and sorting
-   Search functionality
-   Eager loading of relationships

### Security

-   Authentication required for all endpoints
-   User ownership validation
-   Input validation and sanitization
-   Rate limiting ready

## 📝 Code Examples

### Create Order

```bash
curl -X POST "http://localhost:8000/api/orders" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+966501234567",
    "items": [
      {
        "product_id": 1,
        "quantity": 2,
        "options": [1, 2]
      }
    ]
  }'
```

### Get Orders with Filters

```bash
curl -X GET "http://localhost:8000/api/orders?status=pending&per_page=10" \
  -H "Authorization: Bearer {token}"
```

### Create Appointment

```bash
curl -X POST "http://localhost:8000/api/appointments" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "designer_id": 5,
    "appointment_date": "2025-08-31",
    "appointment_time": "14:00",
    "service_type": "Logo Design",
    "description": "Create a modern logo for my business",
    "duration": 60
  }'
```

### Get Available Time Slots

```bash
curl -X GET "http://localhost:8000/api/appointments/available-slots?designer_id=5&date=2025-08-31" \
  -H "Authorization: Bearer {token}"
```

## 🚨 Error Handling

### Common HTTP Status Codes

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error

### Error Response Format

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Error message"]
    }
}
```

## 🔄 API Versioning

The API is designed to support versioning. Current version is v1, accessed via `/api/` endpoints.

## 📞 Support

For API-related questions:

1. Check the relevant documentation first
2. Review the code examples
3. Test with the provided scripts
4. Create an issue for bugs or missing features

---

**Last Updated:** August 30, 2025  
**API Version:** v1
