# Support Tickets API Documentation

## Overview

The Support Tickets API allows users to create, manage, and follow up on support tickets. This system provides a comprehensive solution for customer support with features like ticket categorization, priority levels, file attachments, and admin management.

## Features

- ✅ Create support tickets with different priorities and categories
- ✅ Add replies and follow up on tickets
- ✅ File attachments support
- ✅ Ticket status tracking (open, in_progress, pending, resolved, closed)
- ✅ Priority levels (low, medium, high, urgent)
- ✅ Category classification (technical, billing, general, feature_request, bug_report)
- ✅ Admin assignment and management
- ✅ Statistics and reporting
- ✅ Bulk operations for admins

## Database Schema

### Support Tickets Table
```sql
- id (bigint, primary key)
- ticket_number (string, unique) - Auto-generated format: TKT-YYYY-XXXXXX
- user_id (bigint, foreign key to users table)
- subject (string, max 255)
- description (text, max 5000)
- priority (enum: low, medium, high, urgent)
- status (enum: open, in_progress, pending, resolved, closed)
- category (enum: technical, billing, general, feature_request, bug_report)
- assigned_to (bigint, foreign key to admins table, nullable)
- resolved_at (timestamp, nullable)
- closed_at (timestamp, nullable)
- attachments (json, nullable) - File paths
- admin_notes (text, nullable) - Internal admin notes
- created_at, updated_at (timestamps)
```

### Support Ticket Replies Table
```sql
- id (bigint, primary key)
- ticket_id (bigint, foreign key to support_tickets table)
- user_id (bigint, foreign key to users table, nullable)
- admin_id (bigint, foreign key to admins table, nullable)
- message (text, max 5000)
- attachments (json, nullable) - File paths
- is_internal (boolean) - Internal admin notes
- created_at, updated_at (timestamps)
```

## API Endpoints

### User Endpoints

#### 1. Get Support Tickets
```http
GET /api/support-tickets
```

**Query Parameters:**
- `status` - Filter by status (open, in_progress, pending, resolved, closed)
- `priority` - Filter by priority (low, medium, high, urgent)
- `category` - Filter by category (technical, billing, general, feature_request, bug_report)
- `page` - Page number for pagination (default: 1)

**Response:**
```json
{
  "success": true,
  "data": {
    "tickets": [
      {
        "id": 1,
        "ticket_number": "TKT-2025-ABC123",
        "subject": "Login Issue",
        "description": "Cannot login to account",
        "priority": "high",
        "priority_badge": "warning",
        "status": "open",
        "status_badge": "success",
        "category": "technical",
        "category_badge": "danger",
        "attachments": [],
        "is_open": true,
        "can_be_modified": true,
        "created_at": "2025-01-13T02:15:32.000000Z",
        "updated_at": "2025-01-13T02:15:32.000000Z",
        "resolved_at": null,
        "closed_at": null,
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        },
        "assigned_admin": null,
        "replies_count": 0,
        "latest_reply": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 1,
      "has_more_pages": false
    }
  }
}
```

#### 2. Create Support Ticket
```http
POST /api/support-tickets
```

**Request Body:**
```json
{
  "subject": "Login Issue",
  "description": "I cannot login to my account. I keep getting an error message.",
  "priority": "high",
  "category": "technical",
  "attachments": []
}
```

**Response:**
```json
{
  "success": true,
  "message": "Support ticket created successfully",
  "data": {
    "id": 1,
    "ticket_number": "TKT-2025-ABC123",
    "subject": "Login Issue",
    "description": "I cannot login to my account...",
    "priority": "high",
    "status": "open",
    "category": "technical",
    "created_at": "2025-01-13T02:15:32.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

#### 3. Get Specific Support Ticket
```http
GET /api/support-tickets/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "ticket_number": "TKT-2025-ABC123",
    "subject": "Login Issue",
    "description": "I cannot login to my account...",
    "priority": "high",
    "status": "in_progress",
    "category": "technical",
    "created_at": "2025-01-13T02:15:32.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "assigned_admin": {
      "id": 1,
      "name": "Admin User"
    },
    "replies": [
      {
        "id": 1,
        "message": "Thank you for reporting this issue...",
        "author_type": "admin",
        "author_name": "Admin User (Admin)",
        "created_at": "2025-01-13T02:20:15.000000Z",
        "attachments": []
      }
    ]
  }
}
```

#### 4. Add Reply to Support Ticket
```http
POST /api/support-tickets/{id}/replies
```

**Request Body:**
```json
{
  "message": "Thank you for the update. I have tried the suggested solution but it didn't work.",
  "attachments": []
}
```

**Response:**
```json
{
  "success": true,
  "message": "Reply added successfully",
  "data": {
    "id": 2,
    "message": "Thank you for the update...",
    "attachments": [],
    "is_internal": false,
    "author_type": "user",
    "author_name": "John Doe",
    "created_at": "2025-01-13T02:25:30.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

#### 5. Get Support Ticket Replies
```http
GET /api/support-tickets/{id}/replies
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "message": "Thank you for reporting this issue...",
      "author_type": "admin",
      "author_name": "Admin User (Admin)",
      "created_at": "2025-01-13T02:20:15.000000Z",
      "attachments": []
    },
    {
      "id": 2,
      "message": "Thank you for the update...",
      "author_type": "user",
      "author_name": "John Doe",
      "created_at": "2025-01-13T02:25:30.000000Z",
      "attachments": []
    }
  ]
}
```

#### 6. Get Support Ticket Statistics
```http
GET /api/support-tickets/statistics
```

**Response:**
```json
{
  "success": true,
  "data": {
    "total": 10,
    "open": 3,
    "closed": 7,
    "by_priority": {
      "high": 2,
      "medium": 5,
      "low": 3
    },
    "by_category": {
      "technical": 4,
      "billing": 3,
      "general": 2,
      "feature_request": 1
    }
  }
}
```

## Admin Panel Endpoints

### Admin Routes (Web Interface)

- `GET /admin/support-tickets` - List all support tickets with filtering
- `GET /admin/support-tickets/{id}` - View ticket details
- `GET /admin/support-tickets/{id}/edit` - Edit ticket form
- `PUT /admin/support-tickets/{id}` - Update ticket
- `DELETE /admin/support-tickets/{id}` - Delete ticket
- `POST /admin/support-tickets/{id}/assign` - Assign ticket to admin
- `POST /admin/support-tickets/{id}/replies` - Add admin reply
- `PUT /admin/support-tickets/replies/{reply}` - Update reply
- `DELETE /admin/support-tickets/replies/{reply}` - Delete reply
- `POST /admin/support-tickets/bulk-action` - Bulk actions
- `GET /admin/support-tickets-statistics` - Admin statistics

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "subject": ["The subject field is required."],
    "priority": ["The priority must be one of: low, medium, high, urgent."]
  }
}
```

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
  "message": "Access denied"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Support ticket not found"
}
```

## Validation Rules

### Support Ticket Creation
- `subject`: required, string, max 255 characters
- `description`: required, string, max 5000 characters
- `priority`: required, must be one of: low, medium, high, urgent
- `category`: required, must be one of: technical, billing, general, feature_request, bug_report
- `attachments`: optional, array, max 5 files, each file max 10MB

### Support Ticket Reply
- `message`: required, string, max 5000 characters
- `attachments`: optional, array, max 5 files, each file max 10MB

### Allowed File Types
- PDF, JPG, JPEG, PNG, DOC, DOCX, TXT

## Status Flow

```
open → in_progress → pending → resolved → closed
  ↑         ↓           ↓         ↓
  └─────────┴───────────┴─────────┘
```

- **open**: New ticket created by user
- **in_progress**: Assigned to admin and being worked on
- **pending**: Waiting for user response or external action
- **resolved**: Issue has been resolved
- **closed**: Ticket is closed (cannot be reopened)

## Priority Levels

- **urgent**: Critical issues requiring immediate attention
- **high**: Important issues that should be resolved quickly
- **medium**: Standard priority issues
- **low**: Minor issues or feature requests

## Categories

- **technical**: Technical issues, bugs, system problems
- **billing**: Payment, subscription, billing questions
- **general**: General inquiries, account questions
- **feature_request**: Requests for new features
- **bug_report**: Bug reports and issues

## Usage Examples

### PHP cURL Example
```php
// Create a support ticket
$data = [
    'subject' => 'Login Issue',
    'description' => 'Cannot login to my account',
    'priority' => 'high',
    'category' => 'technical'
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost:8000/api/support-tickets',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]
]);

$response = curl_exec($ch);
curl_close($ch);
```

### JavaScript Fetch Example
```javascript
// Get support tickets
const response = await fetch('/api/support-tickets?status=open', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});

const data = await response.json();
console.log(data.data.tickets);
```

## Testing

Use the provided test file `test-files/api/test_support_tickets_api.php` to test all endpoints. Make sure to:

1. Replace `$apiToken` with a valid authentication token
2. Update `$baseUrl` to match your API URL
3. Run the test to verify all endpoints work correctly

## Security Considerations

- All endpoints require authentication via Bearer token
- Users can only access their own tickets and replies
- Admins have full access to all tickets
- File uploads are validated for type and size
- Internal admin notes are not visible to users

## Future Enhancements

- Email notifications for ticket updates
- Ticket templates
- Knowledge base integration
- SLA tracking
- Customer satisfaction surveys
- Advanced reporting and analytics


