# Velorena API Documentation

## Overview

This project uses **Scribe** to automatically generate beautiful API documentation from your Laravel routes and controllers.

## Accessing the Documentation

### Local Development
- **HTML Documentation**: http://localhost:8000/docs
- **Postman Collection**: http://localhost:8000/docs/collection.json
- **OpenAPI Spec**: http://localhost:8000/docs/openapi.yaml

### Production
- **HTML Documentation**: https://yourdomain.com/docs
- **Postman Collection**: https://yourdomain.com/docs/collection.json
- **OpenAPI Spec**: https://yourdomain.com/docs/openapi.yaml

## Features

✅ **Interactive Documentation**: Test API endpoints directly from the browser
✅ **Authentication Support**: Bearer token authentication
✅ **Request/Response Examples**: Real examples with proper formatting
✅ **Postman Collection**: Import directly into Postman
✅ **OpenAPI Specification**: Compatible with Swagger UI and other tools
✅ **Automatic Updates**: Regenerates when you run the command

## Current API Endpoints

### Authentication
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user (authenticated)
- `POST /api/auth/send-otp` - Send OTP
- `POST /api/auth/verify-otp` - Verify OTP
- `POST /api/auth/resend-otp` - Resend OTP

### User Profile
- `GET /api/profile` - Get user profile (authenticated)
- `PUT /api/profile` - Update user profile (authenticated)

### Documents
- `POST /api/documents/upload` - Upload document (authenticated)
- `DELETE /api/documents/delete` - Delete document (authenticated)
- `GET /api/documents/info` - Get document info (authenticated)

## Regenerating Documentation

To update the documentation after making changes to your API:

```bash
php artisan scribe:generate
```

## Configuration

The documentation is configured in `config/scribe.php`. Key settings:

- **Routes**: Only API routes under `/api/*` are documented
- **Authentication**: Bearer token authentication is configured
- **Groups**: Endpoints are grouped by functionality (Authentication, User Profile, etc.)
- **Output**: Static HTML files generated in `public/docs/`

## Adding Documentation to New Endpoints

To add documentation to new endpoints, add docblock comments to your controller methods:

```php
/**
 * @group Authentication
 * 
 * @bodyParam email string required The email address. Example: user@example.com
 * @bodyParam password string required The password. Example: password123
 * 
 * @response 200 {
 *   "success": true,
 *   "message": "Login successful"
 * }
 */
public function login(Request $request)
{
    // Your code here
}
```

## Troubleshooting

### Database Transaction Errors
If you see database transaction errors during generation, make sure:
1. Your database is running
2. Database connection is properly configured
3. You have the necessary tables and data

### Missing Endpoints
If endpoints are missing from documentation:
1. Check that they match the route patterns in `config/scribe.php`
2. Ensure they have proper docblock comments
3. Verify the routes are accessible

## Benefits of Scribe

- **Zero Configuration**: Works out of the box
- **Beautiful UI**: Modern, responsive interface
- **Interactive Testing**: Test APIs directly from docs
- **Automatic Discovery**: Scans routes and controllers automatically
- **Multiple Formats**: HTML, Postman, OpenAPI
- **Active Development**: Well-maintained with regular updates

## Comparison with Other Tools

| Feature | Scribe | L5-Swagger | Laravel OpenAPI |
|---------|--------|------------|-----------------|
| Setup Difficulty | ⭐⭐⭐⭐⭐ (Easiest) | ⭐⭐⭐⭐ | ⭐⭐⭐ |
| Customization | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| UI Quality | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ |
| Learning Curve | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Maintenance | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ |

**Scribe is recommended for its ease of use and beautiful output.**
