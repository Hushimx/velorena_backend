# File Upload Examples

## Using cURL

### Upload CR Document
```bash
curl -X POST http://localhost:8000/api/documents/upload \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "document=@/path/to/your/cr_document.pdf" \
  -F "type=cr_document"
```

### Upload VAT Document
```bash
curl -X POST http://localhost:8000/api/documents/upload \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "document=@/path/to/your/vat_document.pdf" \
  -F "type=vat_document"
```

### Delete Document
```bash
curl -X DELETE http://localhost:8000/api/documents/delete \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"type": "cr_document"}'
```

### Get Document Info
```bash
curl -X GET "http://localhost:8000/api/documents/info?type=cr_document" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Using JavaScript (Fetch API)

### Upload Document
```javascript
const uploadDocument = async (file, type, token) => {
  const formData = new FormData();
  formData.append('document', file);
  formData.append('type', type);

  const response = await fetch('http://localhost:8000/api/documents/upload', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`
    },
    body: formData
  });

  return await response.json();
};

// Usage
const fileInput = document.getElementById('document');
const file = fileInput.files[0];
const result = await uploadDocument(file, 'cr_document', 'YOUR_TOKEN_HERE');
console.log(result);
```

### Delete Document
```javascript
const deleteDocument = async (type, token) => {
  const response = await fetch('http://localhost:8000/api/documents/delete', {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ type })
  });

  return await response.json();
};

// Usage
const result = await deleteDocument('cr_document', 'YOUR_TOKEN_HERE');
console.log(result);
```

## Using PHP

### Upload Document
```php
<?php

function uploadDocument($filePath, $type, $token) {
    $url = 'http://localhost:8000/api/documents/upload';
    
    $postData = [
        'document' => new CURLFile($filePath),
        'type' => $type
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Usage
$result = uploadDocument('/path/to/document.pdf', 'cr_document', 'YOUR_TOKEN_HERE');
print_r($result);
```

## Using Python (requests)

### Upload Document
```python
import requests

def upload_document(file_path, document_type, token):
    url = 'http://localhost:8000/api/documents/upload'
    
    with open(file_path, 'rb') as file:
        files = {'document': file}
        data = {'type': document_type}
        headers = {'Authorization': f'Bearer {token}'}
        
        response = requests.post(url, files=files, data=data, headers=headers)
        return response.json()

# Usage
result = upload_document('/path/to/document.pdf', 'cr_document', 'YOUR_TOKEN_HERE')
print(result)
```

### Delete Document
```python
import requests

def delete_document(document_type, token):
    url = 'http://localhost:8000/api/documents/delete'
    
    data = {'type': document_type}
    headers = {
        'Authorization': f'Bearer {token}',
        'Content-Type': 'application/json'
    }
    
    response = requests.delete(url, json=data, headers=headers)
    return response.json()

# Usage
result = delete_document('cr_document', 'YOUR_TOKEN_HERE')
print(result)
```

## Using Postman

### Upload Document
1. **Method**: POST
2. **URL**: `http://localhost:8000/api/documents/upload`
3. **Headers**:
   - `Authorization`: `Bearer YOUR_TOKEN_HERE`
4. **Body**: Form-data
   - `document`: [Select File] (Type: File)
   - `type`: `cr_document` (Type: Text)

### Delete Document
1. **Method**: DELETE
2. **URL**: `http://localhost:8000/api/documents/delete`
3. **Headers**:
   - `Authorization`: `Bearer YOUR_TOKEN_HERE`
   - `Content-Type`: `application/json`
4. **Body**: Raw (JSON)
   ```json
   {
       "type": "cr_document"
   }
   ```

## Using Axios (JavaScript)

### Upload Document
```javascript
import axios from 'axios';

const uploadDocument = async (file, type, token) => {
  const formData = new FormData();
  formData.append('document', file);
  formData.append('type', type);

  const response = await axios.post('http://localhost:8000/api/documents/upload', formData, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'multipart/form-data'
    }
  });

  return response.data;
};

// Usage
const fileInput = document.getElementById('document');
const file = fileInput.files[0];
const result = await uploadDocument(file, 'cr_document', 'YOUR_TOKEN_HERE');
console.log(result);
```

## File Upload Validation

### Supported File Types
- PDF (.pdf)
- JPEG (.jpg, .jpeg)
- PNG (.png)

### File Size Limits
- Maximum: 5MB per file

### Error Responses
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "document": [
            "The document field must be a file.",
            "The document field must be a file of type: pdf, jpg, jpeg, png.",
            "The document field must not be greater than 5120 kilobytes."
        ]
    }
}
```

## Complete Workflow Example

1. **Register/Login** to get a token
2. **Upload CR Document**:
   ```bash
   curl -X POST http://localhost:8000/api/documents/upload \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -F "document=@cr_document.pdf" \
     -F "type=cr_document"
   ```
3. **Upload VAT Document**:
   ```bash
   curl -X POST http://localhost:8000/api/documents/upload \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -F "document=@vat_document.pdf" \
     -F "type=vat_document"
   ```
4. **Check Document Info**:
   ```bash
   curl -X GET "http://localhost:8000/api/documents/info?type=cr_document" \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```
5. **View Profile** (includes document URLs):
   ```bash
   curl -X GET http://localhost:8000/api/profile \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

## Security Notes

- All upload endpoints require authentication
- Files are validated for type and size
- Files are stored with unique names to prevent conflicts
- Users can only access their own documents
- Files are stored in the public storage directory for easy access
