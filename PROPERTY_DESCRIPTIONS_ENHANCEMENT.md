# Enhanced Property Descriptions in Swagger Documentation

## Overview

This document outlines the comprehensive enhancements made to add detailed property descriptions for all JSON request/response bodies in the Swagger documentation. Each property now has a clear, descriptive explanation of its purpose, format, and usage.

## Key Enhancements Made

### 1. **Authentication Endpoints - Request Properties**

#### User Registration (`POST /api/auth/register`)
**Enhanced Properties:**
- **`client_type`**: "Type of client account - individual person or company"
- **`full_name`**: "Full name of the individual or primary contact person"
- **`company_name`**: "Company name (required if client_type is 'company')"
- **`contact_person`**: "Name of the contact person for company accounts"
- **`email`**: "Valid email address for account login and communications"
- **`phone`**: "Phone number with country code for contact purposes"
- **`address`**: "Street address for billing and delivery purposes"
- **`city`**: "City name for billing and delivery purposes"
- **`country`**: "Country name for billing and delivery purposes"
- **`vat_number`**: "VAT registration number for tax purposes (optional)"
- **`cr_number`**: "Commercial registration number for business accounts (optional)"
- **`notes`**: "Additional notes or special requirements for the account"
- **`password`**: "Account password (minimum 8 characters)"
- **`password_confirmation`**: "Password confirmation (must match password field)"

#### User Login (`POST /api/auth/login`)
**Enhanced Properties:**
- **`email`**: "Registered email address for the account"
- **`password`**: "Account password for authentication"

#### Profile Update (`PUT /api/profile`)
**Enhanced Properties:**
- All properties from registration with detailed descriptions
- Clear indication of which fields are optional vs required

### 2. **OTP Endpoints - Request Properties**

#### Send OTP (`POST /api/auth/send-otp`)
**Enhanced Properties:**
- **`identifier`**: "Email address or phone number where the OTP will be sent. For email OTP, use a valid email format. For SMS/WhatsApp, use international phone number format with country code."
- **`type`**: "Delivery method for the OTP. 'email' sends via email, 'sms' sends via text message, 'whatsapp' sends via WhatsApp, 'fake' generates a test OTP for development"
- **`expiry_minutes`**: "Number of minutes before the OTP expires. Must be between 1-60 minutes. Default is 10 minutes if not specified."

#### Verify OTP (`POST /api/auth/verify-otp`)
**Enhanced Properties:**
- **`identifier`**: "Email address or phone number that was used when the OTP was sent. Must match exactly with the identifier used in the send-otp request."
- **`code`**: "6-digit OTP code received via the specified delivery method. Must be exactly 6 characters long."
- **`type`**: "Delivery method that was used to send the OTP. Must match the type used in the send-otp request."

#### Resend OTP (`POST /api/auth/resend-otp`)
**Enhanced Properties:**
- **`identifier`**: "Email address or phone number where the new OTP will be sent. Must be the same identifier used in the original send-otp request."
- **`type`**: "Delivery method for the new OTP. Must be the same type used in the original send-otp request."
- **`expiry_minutes`**: "Number of minutes before the new OTP expires. Must be between 1-60 minutes. Default is 10 minutes if not specified."

### 3. **Document Management - Request Properties**

#### Upload Document (`POST /api/documents/upload`)
**Enhanced Properties:**
- **`type`**: "Type of document being uploaded. 'cr_document' for Commercial Registration documents, 'vat_document' for VAT registration documents."
- **`document`**: "Document file to upload. Supported formats: PDF, JPG, PNG, DOC, DOCX. Maximum file size: 10MB. File should be clear and readable."

#### Delete Document (`DELETE /api/documents/delete`)
**Enhanced Properties:**
- **`type`**: "Type of document to delete. 'cr_document' for Commercial Registration documents, 'vat_document' for VAT registration documents. This will permanently remove the document from storage."

#### Get Document Info (`GET /api/documents/info`)
**Enhanced Properties:**
- **`type`**: "Type of document to get information for. 'cr_document' for Commercial Registration documents, 'vat_document' for VAT registration documents."

### 4. **Document Management - Response Properties**

#### Document Info Response
**Enhanced Properties:**
- **`type`**: "Type of document (cr_document or vat_document)"
- **`filename`**: "Original filename of the uploaded document"
- **`url`**: "Public URL to access/download the document"
- **`size`**: "File size in bytes"
- **`exists`**: "Whether the file exists in storage"
- **`uploaded_at`**: "Timestamp when the document was uploaded"

### 5. **Product & Category Endpoints - Query Parameters**

#### Products (`GET /api/products`)
**Enhanced Parameters:**
- **`category_id`**: "Filter products by specific category ID. Only products from this category will be returned."
- **`search`**: "Search products by name in English or Arabic. Performs partial matching on product names."
- **`page`**: "Page number for pagination. Starts from 1."
- **`limit`**: "Number of products to return per page. Minimum: 1, Maximum: 100, Default: 15."

#### Categories (`GET /api/categories`)
**Enhanced Parameters:**
- **`search`**: "Search categories by name in English or Arabic. Performs partial matching on category names."
- **`page`**: "Page number for pagination. Starts from 1."
- **`limit`**: "Number of categories to return per page. Minimum: 1, Maximum: 100, Default: 15."

## Benefits of Enhanced Property Descriptions

### 1. **Developer Clarity**
- **Clear Purpose**: Each property now explains exactly what it's used for
- **Format Requirements**: Developers know the expected format (email, phone with country code, etc.)
- **Validation Rules**: Clear indication of required vs optional fields
- **Business Logic**: Understanding of when certain fields are needed (e.g., company_name for company accounts)

### 2. **API Testing**
- **Better Examples**: Developers can create more realistic test data
- **Error Prevention**: Understanding of validation requirements reduces failed requests
- **Complete Testing**: All possible scenarios are clearly documented

### 3. **Integration Support**
- **Frontend Development**: Clear understanding of what data to collect from users
- **Third-party Integration**: External developers can easily understand the API
- **Documentation**: Self-documenting API that reduces support requests

## Examples of Enhanced Descriptions

### Before Enhancement:
```json
{
  "client_type": "individual",
  "email": "john@example.com"
}
```

### After Enhancement:
```json
{
  "client_type": {
    "description": "Type of client account - individual person or company",
    "enum": ["individual", "company"],
    "example": "individual"
  },
  "email": {
    "description": "Valid email address for account login and communications",
    "format": "email",
    "example": "john@example.com"
  }
}
```

## Technical Implementation

### 1. **OpenAPI 3.0 Format**
All property descriptions follow the OpenAPI 3.0 specification:
- `@OA\Property` annotations with `description` field
- Proper `type`, `format`, and `example` specifications
- Enum values clearly defined where applicable

### 2. **Consistent Structure**
- All descriptions follow a consistent format
- Clear, concise language
- Practical examples provided
- Validation rules explained

### 3. **Comprehensive Coverage**
- All request body properties documented
- All response body properties documented
- All query parameters documented
- All path parameters documented

## How to Access Enhanced Documentation

### Swagger UI
- **URL**: `/api/documentation`
- **Alternative**: `/docs`

### JSON Format
- **URL**: `/api/documentation/json`

### Regeneration
To regenerate after code changes:
```bash
php artisan l5-swagger:generate
```

## Conclusion

The enhanced property descriptions make the API documentation **much more comprehensive and user-friendly**. Developers now have:

- **Clear understanding** of what each field does
- **Proper format guidance** for all inputs
- **Validation requirements** clearly stated
- **Realistic examples** for testing
- **Business logic context** for complex fields

This significantly improves the developer experience and reduces integration time for new API consumers.


