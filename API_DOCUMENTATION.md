# API Documentation

## Authentication Endpoints

### Register User
**POST** `/api/auth/register`

Register a new user (individual or company).

#### Request Body
```json
{
    "client_type": "individual", // or "company"
    "full_name": "John Doe", // required for individual
    "company_name": "Company Name", // required for company
    "contact_person": "Contact Person", // required for company
    "email": "user@example.com",
    "phone": "+1234567890",
    "address": "Full Address",
    "city": "City Name",
    "country": "Country Name",
    "vat_number": "VAT123456", // for companies
    "cr_number": "CR123456", // for companies
    "notes": "Additional notes",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Response
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "client_type": "individual",
            "full_name": "John Doe",
            "email": "user@example.com",
            "phone": "+1234567890"
        },
        "token": "1|abc123...",
        "otp_sent": true
    }
}
```

### Login User
**POST** `/api/auth/login`

Login with email and password.

#### Request Body
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

#### Response
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "client_type": "individual",
            "full_name": "John Doe",
            "email": "user@example.com",
            "phone": "+1234567890"
        },
        "token": "1|abc123..."
    }
}
```

### Logout User
**POST** `/api/auth/logout`

Logout and invalidate the current token.

**Headers:** `Authorization: Bearer {token}`

#### Response
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### Get User Profile
**GET** `/api/profile`

Get the authenticated user's profile.

**Headers:** `Authorization: Bearer {token}`

#### Response
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "client_type": "individual",
            "full_name": "John Doe",
            "company_name": null,
            "contact_person": null,
            "email": "user@example.com",
            "phone": "+1234567890",
            "address": "Full Address",
            "city": "City Name",
            "country": "Country Name",
            "vat_number": null,
            "cr_number": null,
            "notes": "Additional notes",
            "cr_document_url": "http://localhost:8000/storage/documents/123456_1_cr_document.pdf",
            "vat_document_url": null,
            "created_at": "2025-08-17T16:00:00.000000Z"
        }
    }
}
```

### Update User Profile
**PUT** `/api/profile`

Update the authenticated user's profile.

**Headers:** `Authorization: Bearer {token}`

#### Request Body
```json
{
    "client_type": "individual",
    "full_name": "John Doe Updated",
    "phone": "+1234567890",
    "address": "Updated Address",
    "city": "Updated City",
    "country": "Updated Country",
    "notes": "Updated notes"
}
```

#### Response
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "user": {
            "id": 1,
            "client_type": "individual",
            "full_name": "John Doe Updated",
            "email": "user@example.com",
            "phone": "+1234567890",
            "address": "Updated Address",
            "city": "Updated City",
            "country": "Updated Country",
            "notes": "Updated notes",
            "cr_document_url": "http://localhost:8000/storage/documents/123456_1_cr_document.pdf",
            "vat_document_url": null,
            "updated_at": "2025-08-17T16:00:00.000000Z"
        }
    }
}
```

## Document Upload Endpoints

### Upload Document
**POST** `/api/documents/upload`

Upload a document (CR document or VAT document).

**Headers:** `Authorization: Bearer {token}`

**Content-Type:** `multipart/form-data`

#### Request Body (Form Data)
```
document: [file] // PDF, JPG, JPEG, or PNG file (max 5MB)
type: "cr_document" // or "vat_document"
```

#### Response
```json
{
    "success": true,
    "message": "Document uploaded successfully",
    "data": {
        "filename": "1734567890_1_cr_document.pdf",
        "path": "documents/1734567890_1_cr_document.pdf",
        "url": "http://localhost:8000/storage/documents/1734567890_1_cr_document.pdf",
        "size": 102400,
        "mime_type": "application/pdf",
        "type": "cr_document"
    }
}
```

### Delete Document
**DELETE** `/api/documents/delete`

Delete a document.

**Headers:** `Authorization: Bearer {token}`

#### Request Body
```json
{
    "type": "cr_document" // or "vat_document"
}
```

#### Response
```json
{
    "success": true,
    "message": "Document deleted successfully"
}
```

### Get Document Info
**GET** `/api/documents/info`

Get information about a document.

**Headers:** `Authorization: Bearer {token}`

#### Query Parameters
```
type: "cr_document" // or "vat_document"
```

#### Response
```json
{
    "success": true,
    "data": {
        "type": "cr_document",
        "filename": "1734567890_1_cr_document.pdf",
        "url": "http://localhost:8000/storage/documents/1734567890_1_cr_document.pdf",
        "size": 102400,
        "exists": true,
        "uploaded_at": "2025-08-17T16:00:00.000000Z"
    }
}
```

## OTP Endpoints

### Send OTP
**POST** `/api/auth/send-otp`

Send OTP via email, SMS, WhatsApp, or fake (for testing).

#### Request Body
```json
{
    "identifier": "user@example.com", // email or phone number
    "type": "email", // "email", "sms", "whatsapp", or "fake"
    "expiry_minutes": 10 // optional, default 10
}
```

#### Response
```json
{
    "success": true,
    "message": "OTP sent successfully via email",
    "data": {
        "otp_id": 1,
        "expires_at": "2025-08-17T16:10:00.000000Z",
        "type": "email"
    }
}
```

### Verify OTP
**POST** `/api/auth/verify-otp`

Verify OTP code.

#### Request Body
```json
{
    "identifier": "user@example.com",
    "code": "123456",
    "type": "email"
}
```

#### Response
```json
{
    "success": true,
    "message": "OTP verified successfully",
    "data": {
        "verified_at": "2025-08-17T16:00:00.000000Z",
        "otp_id": 1
    }
}
```

### Resend OTP
**POST** `/api/auth/resend-otp`

Resend OTP (invalidates previous unused OTPs).

#### Request Body
```json
{
    "identifier": "user@example.com",
    "type": "email",
    "expiry_minutes": 10 // optional, default 10
}
```

#### Response
```json
{
    "success": true,
    "message": "OTP resent successfully",
    "data": {
        "otp_id": 2,
        "expires_at": "2025-08-17T16:10:00.000000Z",
        "type": "email"
    }
}
```

## File Upload Specifications

### Supported File Types
- **PDF** (.pdf)
- **JPEG** (.jpg, .jpeg)
- **PNG** (.png)

### File Size Limits
- **Maximum Size**: 5MB per file

### File Storage
- **Storage Location**: `storage/app/public/documents/`
- **Public URL**: `http://your-domain.com/storage/documents/{filename}`
- **File Naming**: `{timestamp}_{user_id}_{document_type}.{extension}`

### Security Features
- **File Type Validation**: Only allowed file types are accepted
- **Size Validation**: Files exceeding 5MB are rejected
- **Authentication Required**: All upload operations require valid token
- **User Isolation**: Users can only access their own documents

## OTP Types

1. **Email OTP**: Sends OTP via email (currently logs to Laravel logs)
2. **SMS OTP**: Sends OTP via SMS (currently logs to Laravel logs)
3. **WhatsApp OTP**: Sends OTP via WhatsApp (currently logs to Laravel logs)
4. **Fake OTP**: For testing purposes, logs OTP to Laravel logs

## Error Responses

All endpoints return consistent error responses:

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

## Authentication

Use Bearer token authentication for protected endpoints:

```
Authorization: Bearer {your_token}
```

## Testing

### File Upload Testing
```bash
# Upload CR document
curl -X POST http://localhost:8000/api/documents/upload \
  -H "Authorization: Bearer {your_token}" \
  -F "document=@/path/to/document.pdf" \
  -F "type=cr_document"

# Upload VAT document
curl -X POST http://localhost:8000/api/documents/upload \
  -H "Authorization: Bearer {your_token}" \
  -F "document=@/path/to/vat.pdf" \
  -F "type=vat_document"
```

### OTP Testing
For testing OTP functionality, use the "fake" type which will log the OTP code to Laravel logs instead of actually sending it.

## Setup Instructions

1. **Storage Link**: Run `php artisan storage:link` to create public storage link
2. **File Permissions**: Ensure `storage/app/public/documents/` is writable
3. **Database**: Run migrations with `php artisan migrate`
4. **Testing**: Run tests with `php artisan test`

## File Upload Flow

1. **User Authentication**: User must be logged in with valid token
2. **File Validation**: System validates file type and size
3. **File Storage**: File is stored in `storage/app/public/documents/`
4. **Database Update**: User's document path is updated in database
5. **Response**: System returns file information including public URL
