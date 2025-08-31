# Appointments API Documentation

## Overview

This document describes the Appointments API endpoints for managing user appointments. All endpoints require authentication using Laravel Sanctum.

## Base URL

```
http://your-domain.com/api
```

## Authentication

All endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. Get User Appointments

**GET** `/api/appointments`

Retrieve all appointments for the authenticated user.

#### Query Parameters

-   `status` (optional): Filter by appointment status (pending, confirmed, cancelled, completed)
-   `designer_id` (optional): Filter by designer ID
-   `date_from` (optional): Filter appointments from this date (YYYY-MM-DD)
-   `date_to` (optional): Filter appointments until this date (YYYY-MM-DD)
-   `search` (optional): Search in service type, description, or notes
-   `sort_by` (optional): Sort field (appointment_date, created_at, status) - default: appointment_date
-   `sort_order` (optional): Sort direction (asc, desc) - default: asc
-   `per_page` (optional): Items per page (1-100) - default: 15
-   `page` (optional): Page number - default: 1

#### Example Request

```bash
curl -X GET "http://your-domain.com/api/appointments?status=pending&date_from=2025-08-01&per_page=10" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Appointments retrieved successfully",
    "data": {
        "appointments": [
            {
                "id": 1,
                "user_id": 51,
                "designer_id": 5,
                "appointment_date": "2025-08-31",
                "appointment_time": "14:00",
                "service_type": "Logo Design",
                "description": "Create a modern logo for my business",
                "duration": 60,
                "location": "Office",
                "notes": "Please bring design samples",
                "status": "pending",
                "order_id": 15,
                "order_notes": "Logo design for new brand",
                "created_at": "2025-08-30T10:00:00.000000Z",
                "updated_at": "2025-08-30T10:00:00.000000Z",
                "designer": {
                    "id": 5,
                    "name": "Ahmed Designer",
                    "email": "ahmed@example.com",
                    "phone": "+966501234567",
                    "specialization": "Logo Design",
                    "experience_years": 5,
                    "is_available": true
                },
                "order": {
                    "id": 15,
                    "order_number": "ORD2025080015",
                    "status": "pending",
                    "total": 500.0
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 10,
            "total": 25,
            "from": 1,
            "to": 10
        }
    }
}
```

### 2. Get Designer Appointments

**GET** `/api/designer/appointments`

Retrieve all appointments for the authenticated designer.

#### Query Parameters

-   `status` (optional): Filter by appointment status
-   `date_from` (optional): Filter appointments from this date
-   `date_to` (optional): Filter appointments until this date
-   `sort_by` (optional): Sort field - default: appointment_date
-   `sort_order` (optional): Sort direction - default: asc
-   `per_page` (optional): Items per page - default: 15

#### Example Request

```bash
curl -X GET "http://your-domain.com/api/designer/appointments?status=confirmed&date_from=2025-08-01" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

### 3. Get Specific Appointment

**GET** `/api/appointments/{appointment_id}`

Retrieve details of a specific appointment.

#### Example Request

```bash
curl -X GET "http://your-domain.com/api/appointments/1" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Appointment retrieved successfully",
    "data": {
        "id": 1,
        "user_id": 51,
        "designer_id": 5,
        "appointment_date": "2025-08-31",
        "appointment_time": "14:00",
        "service_type": "Logo Design",
        "description": "Create a modern logo for my business",
        "duration": 60,
        "location": "Office",
        "notes": "Please bring design samples",
        "status": "pending",
        "order_id": 15,
        "order_notes": "Logo design for new brand",
        "created_at": "2025-08-30T10:00:00.000000Z",
        "updated_at": "2025-08-30T10:00:00.000000Z",
        "designer": {
            "id": 5,
            "name": "Ahmed Designer",
            "email": "ahmed@example.com",
            "phone": "+966501234567",
            "specialization": "Logo Design",
            "experience_years": 5,
            "is_available": true
        },
        "user": {
            "id": 51,
            "full_name": "John Doe",
            "email": "john@example.com",
            "phone": "+966501234568"
        },
        "order": {
            "id": 15,
            "order_number": "ORD2025080015",
            "status": "pending",
            "total": 500.0
        }
    }
}
```

### 4. Create New Appointment

**POST** `/api/appointments`

Create a new appointment.

#### Request Body

```json
{
    "designer_id": 5,
    "appointment_date": "2025-08-31",
    "appointment_time": "14:00",
    "service_type": "Logo Design",
    "description": "Create a modern logo for my business",
    "duration": 60,
    "location": "Office",
    "notes": "Please bring design samples",
    "order_id": 15,
    "order_notes": "Logo design for new brand"
}
```

#### Field Descriptions

-   `designer_id` (required): Designer ID
-   `appointment_date` (required): Appointment date (YYYY-MM-DD, must be in the future)
-   `appointment_time` (required): Appointment time (HH:MM format)
-   `service_type` (required): Type of service
-   `description` (optional): Detailed description of the service
-   `duration` (optional): Duration in minutes (30-480, default: 60)
-   `location` (optional): Meeting location
-   `notes` (optional): Additional notes
-   `order_id` (optional): Associated order ID
-   `order_notes` (optional): Notes about the associated order

#### Example Request

```bash
curl -X POST "http://your-domain.com/api/appointments" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json" \
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

#### Example Response

```json
{
    "success": true,
    "message": "Appointment created successfully",
    "data": {
        "id": 1,
        "user_id": 51,
        "designer_id": 5,
        "appointment_date": "2025-08-31",
        "appointment_time": "14:00",
        "service_type": "Logo Design",
        "description": "Create a modern logo for my business",
        "duration": 60,
        "status": "pending",
        "created_at": "2025-08-30T10:00:00.000000Z",
        "designer": {
            "id": 5,
            "name": "Ahmed Designer",
            "email": "ahmed@example.com",
            "specialization": "Logo Design"
        }
    }
}
```

### 5. Update Appointment

**PUT** `/api/appointments/{appointment_id}`

Update an existing appointment.

#### Request Body

```json
{
    "appointment_date": "2025-09-01",
    "appointment_time": "15:00",
    "service_type": "Logo Design",
    "description": "Updated description",
    "duration": 90,
    "location": "New Office",
    "notes": "Updated notes"
}
```

#### Example Request

```bash
curl -X PUT "http://your-domain.com/api/appointments/1" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "appointment_date": "2025-09-01",
    "appointment_time": "15:00",
    "duration": 90
  }'
```

### 6. Delete Appointment

**DELETE** `/api/appointments/{appointment_id}`

Delete a pending appointment.

#### Example Request

```bash
curl -X DELETE "http://your-domain.com/api/appointments/1" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Appointment deleted successfully"
}
```

### 7. Cancel Appointment

**POST** `/api/appointments/{appointment_id}/cancel`

Cancel an appointment.

#### Request Body

```json
{
    "reason": "Client requested cancellation"
}
```

#### Example Request

```bash
curl -X POST "http://your-domain.com/api/appointments/1/cancel" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "reason": "Client requested cancellation"
  }'
```

#### Example Response

```json
{
    "success": true,
    "message": "Appointment cancelled successfully",
    "data": {
        "id": 1,
        "status": "cancelled",
        "notes": "Original notes\nCancellation reason: Client requested cancellation"
    }
}
```

### 8. Confirm Appointment

**POST** `/api/appointments/{appointment_id}/confirm`

Confirm a pending appointment.

#### Example Request

```bash
curl -X POST "http://your-domain.com/api/appointments/1/confirm" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Appointment confirmed successfully",
    "data": {
        "id": 1,
        "status": "confirmed"
    }
}
```

### 9. Complete Appointment

**POST** `/api/appointments/{appointment_id}/complete`

Mark an appointment as completed.

#### Example Request

```bash
curl -X POST "http://your-domain.com/api/appointments/1/complete" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Appointment completed successfully",
    "data": {
        "id": 1,
        "status": "completed"
    }
}
```

### 10. Get Available Time Slots

**GET** `/api/appointments/available-slots`

Get available time slots for a designer on a specific date.

#### Query Parameters

-   `designer_id` (required): Designer ID
-   `date` (required): Date to check (YYYY-MM-DD, must be in the future)

#### Example Request

```bash
curl -X GET "http://your-domain.com/api/appointments/available-slots?designer_id=5&date=2025-08-31" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Available time slots retrieved successfully",
    "data": {
        "designer_id": 5,
        "date": "2025-08-31",
        "available_slots": [
            "09:00",
            "10:00",
            "11:00",
            "13:00",
            "14:00",
            "15:00",
            "16:00",
            "17:00"
        ]
    }
}
```

### 11. Get Upcoming Appointments

**GET** `/api/appointments/upcoming`

Get upcoming appointments for the authenticated user.

#### Query Parameters

-   `limit` (optional): Number of appointments to return (1-50, default: 10)

#### Example Request

```bash
curl -X GET "http://your-domain.com/api/appointments/upcoming?limit=5" \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

#### Example Response

```json
{
    "success": true,
    "message": "Upcoming appointments retrieved successfully",
    "data": [
        {
            "id": 1,
            "appointment_date": "2025-08-31",
            "appointment_time": "14:00",
            "service_type": "Logo Design",
            "status": "confirmed",
            "designer": {
                "id": 5,
                "name": "Ahmed Designer",
                "specialization": "Logo Design"
            }
        }
    ]
}
```

## Error Responses

### Validation Error (422)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "designer_id": ["Selected designer does not exist."],
        "appointment_date": ["Appointment date must be in the future."],
        "appointment_time": ["Please enter a valid time format (HH:MM)."]
    }
}
```

### Unauthorized (403)

```json
{
    "success": false,
    "message": "Unauthorized access to appointment"
}
```

### Not Found (404)

```json
{
    "success": false,
    "message": "Appointment not found"
}
```

### Business Logic Error (400)

```json
{
    "success": false,
    "message": "Designer has a conflicting appointment at this time"
}
```

### Server Error (500)

```json
{
    "success": false,
    "message": "Failed to create appointment",
    "error": "Database connection failed"
}
```

## Appointment Status Values

-   `pending`: Appointment is pending confirmation
-   `confirmed`: Appointment has been confirmed
-   `cancelled`: Appointment has been cancelled
-   `completed`: Appointment has been completed

## Business Rules

1. **Appointment Ownership**: Users can only access their own appointments, designers can access appointments assigned to them
2. **Appointment Modification**: Only pending or confirmed appointments can be modified
3. **Appointment Deletion**: Only pending appointments can be deleted
4. **Appointment Cancellation**: Only pending or confirmed appointments can be cancelled
5. **Designer Availability**: Appointments can only be created with available designers
6. **Time Conflicts**: No two appointments can be scheduled for the same designer at the same time
7. **Future Dates**: Appointments can only be scheduled for future dates
8. **Duration Limits**: Appointment duration must be between 30 minutes and 8 hours

## Rate Limiting

-   60 requests per minute per user
-   1000 requests per hour per user

## Testing Examples

### Using Postman

1. Set the Authorization header: `Bearer {your-token}`
2. Set Content-Type header: `application/json`
3. Use the examples above for request bodies

### Using cURL

```bash
# Get appointments
curl -X GET "http://your-domain.com/api/appointments" \
  -H "Authorization: Bearer {your-token}"

# Create appointment
curl -X POST "http://your-domain.com/api/appointments" \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -d '{
    "designer_id": 5,
    "appointment_date": "2025-08-31",
    "appointment_time": "14:00",
    "service_type": "Logo Design"
  }'
```

### Using JavaScript/Fetch

```javascript
const response = await fetch("http://your-domain.com/api/appointments", {
    method: "GET",
    headers: {
        Authorization: "Bearer " + token,
        Accept: "application/json",
    },
});

const data = await response.json();
console.log(data);
```

