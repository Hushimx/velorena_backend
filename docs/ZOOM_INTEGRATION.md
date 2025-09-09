# Zoom Integration Documentation

## Overview

This Laravel application now includes Zoom meeting integration for appointments. When a designer accepts an appointment, a Zoom meeting is automatically created and linked to the appointment.

## Features

- **Automatic Meeting Creation**: Zoom meetings are created when designers accept appointments
- **Meeting Management**: Meetings are automatically deleted when appointments are cancelled
- **API Integration**: Full Zoom API integration with proper error handling
- **Database Storage**: Meeting details are stored in the appointments table
- **API Responses**: Meeting URLs are included in appointment API responses

## Configuration

### 1. Zoom App Setup

1. Go to [Zoom Marketplace](https://marketplace.zoom.us/)
2. Create a new Server-to-Server OAuth app
3. Note down your:
   - Client ID
   - Client Secret
   - Account ID

### 2. Environment Variables

Add these to your `.env` file:

```env
# Zoom API Configuration
ZOOM_CLIENT_ID=your_zoom_client_id_here
ZOOM_CLIENT_SECRET=your_zoom_client_secret_here
ZOOM_ACCOUNT_ID=your_zoom_account_id_here
```

### 3. Database Migration

Run the migration to add Zoom fields to the appointments table:

```bash
php artisan migrate
```

## Database Schema

The following fields are added to the `appointments` table:

- `zoom_meeting_id` - Zoom meeting ID
- `zoom_meeting_url` - Join URL for participants
- `zoom_start_url` - Start URL for hosts
- `zoom_meeting_created_at` - When the meeting was created

## Usage

### Automatic Meeting Creation

Meetings are automatically created when:

1. A designer accepts an appointment (`accept()` method)
2. An appointment is assigned to a designer (`assignToDesigner()` method)

### Manual Meeting Creation

You can manually create a Zoom meeting for an appointment:

```php
$appointment = Appointment::find(1);
$success = $appointment->createZoomMeeting();
```

### Meeting Deletion

Meetings are automatically deleted when:

1. An appointment is cancelled (`cancel()` method)

You can also manually delete a meeting:

```php
$appointment = Appointment::find(1);
$success = $appointment->deleteZoomMeeting();
```

### API Responses

Appointment API responses now include meeting information:

```json
{
  "id": 1,
  "appointment_date": "2025-01-20",
  "appointment_time": "14:00",
  "meeting": {
    "type": "zoom",
    "url": "https://zoom.us/j/123456789",
    "host_url": "https://zoom.us/s/123456789",
    "has_zoom": true,
    "has_google_meet": false,
    "zoom_meeting_id": "123456789",
    "zoom_meeting_url": "https://zoom.us/j/123456789",
    "zoom_start_url": "https://zoom.us/s/123456789",
    "zoom_meeting_created_at": "2025-01-20T10:00:00.000000Z"
  }
}
```

## Testing

### Test Command

Use the provided test command to verify your Zoom integration:

```bash
# Test basic configuration and connection
php artisan zoom:test

# Test with a specific appointment
php artisan zoom:test --appointment-id=1
```

### Manual Testing

1. Create an appointment
2. Assign it to a designer
3. Accept the appointment
4. Check the database for Zoom meeting details
5. Verify the meeting URLs in API responses

## Error Handling

The integration includes comprehensive error handling:

- **Configuration Errors**: Logged when Zoom is not properly configured
- **API Errors**: Logged with full error details
- **Meeting Creation Failures**: Logged but don't prevent appointment acceptance
- **Meeting Deletion Failures**: Logged but don't prevent appointment cancellation

## Logging

All Zoom-related activities are logged:

- Meeting creation success/failure
- Meeting deletion success/failure
- API connection issues
- Configuration problems

Check your Laravel logs for detailed information.

## Troubleshooting

### Common Issues

1. **"Zoom is not configured"**
   - Check your `.env` file has all required Zoom variables
   - Verify the values are correct

2. **"Failed to connect to Zoom API"**
   - Verify your Client ID, Client Secret, and Account ID
   - Check your Zoom app permissions
   - Ensure your Zoom app is approved

3. **"Failed to create Zoom meeting"**
   - Check your Zoom app has meeting creation permissions
   - Verify your account has sufficient Zoom credits
   - Check the Laravel logs for detailed error messages

### Debug Mode

Enable debug logging by setting `LOG_LEVEL=debug` in your `.env` file to see detailed Zoom API interactions.

## Security Considerations

- Keep your Zoom credentials secure
- Use environment variables for all sensitive data
- Regularly rotate your Zoom app credentials
- Monitor your Zoom API usage

## API Rate Limits

Zoom has API rate limits. The integration includes proper error handling for rate limit scenarios, but consider implementing retry logic for production use.

## Support

For issues with the Zoom integration:

1. Check the Laravel logs
2. Run the test command: `php artisan zoom:test`
3. Verify your Zoom app configuration
4. Check Zoom API documentation for any changes
