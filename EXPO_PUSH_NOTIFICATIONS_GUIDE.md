# Expo Push Notifications & WhatsApp Integration Guide

## Overview

This system sends **Expo Push Notifications** and **WhatsApp messages** to users when:
- Order status changes (confirmed, processing, shipped, delivered, cancelled)
- Appointment status changes (accepted, rejected, started, cancelled, completed)

## Architecture

### Backend Components

1. **ExpoPushToken Model** (`app/Models/ExpoPushToken.php`)
   - Stores device push tokens for users (polymorphic relationship)
   - Supports both authenticated users and guest users
   - Tracks device info (platform, device_id, last_used_at)

2. **UnifiedNotificationService** (`app/Services/UnifiedNotificationService.php`)
   - Centralized service for sending both Expo Push and WhatsApp notifications
   - Handles notification content generation based on status changes
   - Gracefully handles failures without breaking the main flow

3. **ExpoPushService** (`app/Services/ExpoPushService.php`)
   - Interfaces with Expo Push Notification API
   - Validates tokens and handles retries
   - Processes batch notifications

4. **WhatsAppService** (`app/Services/WhatsAppService.php`)
   - Sends WhatsApp messages via SmartWats API
   - Handles phone number formatting and validation

### Frontend Components (React Native)

1. **useHybridNotifications Hook** (`velorena_app/hooks/useHybridNotifications.ts`)
   - Registers device for push notifications (guest or authenticated)
   - Handles notification tap events
   - Manages deep linking to appropriate screens

2. **RootLayout Deep Linking** (`velorena_app/app/_layout.tsx`)
   - Handles notifications when app is opened from killed state
   - Routes to correct screen based on notification data

## Database Schema

### expo_push_tokens Table

```sql
id: bigint
token: string (unique) - Expo push token
tokenable_id: bigint (nullable) - User ID (polymorphic)
tokenable_type: string (nullable) - User class (polymorphic)
device_id: string (nullable) - Device identifier
platform: string (nullable) - ios/android/web
is_active: boolean - Whether token is active
last_used_at: timestamp - Last notification sent to this token
created_at: timestamp
updated_at: timestamp
```

## Notification Flow

### Order Status Change

1. Admin updates order status in `OrderController::update()`
2. `UnifiedNotificationService::sendOrderStatusNotification()` is called
3. Service generates notification content based on new status
4. Expo Push notification sent to all active user tokens
5. WhatsApp message sent if user has WhatsApp enabled and phone number

### Appointment Status Change

1. Status changed via Admin Panel or Designer actions:
   - `Admin/AppointmentController::update()` - Admin updates
   - `AppointmentController::accept()` - Designer accepts
   - `AppointmentController::reject()` - Designer rejects
   - `AppointmentController::start()` - Designer starts meeting
   - `AppointmentController::designerCancel()` - Designer cancels
2. `UnifiedNotificationService::sendAppointmentStatusNotification()` is called
3. Service generates notification content
4. Both Expo Push and WhatsApp notifications sent

## Notification Content

### Order Notifications

**Confirmed:**
```
Title: تم تأكيد طلبك
Body: تم تأكيد طلبك رقم {ORDER_NUMBER}
Data: { type: 'order', orderId: 5, status: 'confirmed', screen: 'orders/5' }
```

**Shipped:**
```
Title: تم شحن طلبك
Body: طلبك رقم {ORDER_NUMBER} تم شحنه
Data: { type: 'order', orderId: 5, status: 'shipped', screen: 'orders/5' }
```

### Appointment Notifications

**Accepted:**
```
Title: تم قبول موعدك
Body: تم قبول موعدك مع {DESIGNER_NAME} في {DATE}
Data: { type: 'appointment', appointmentId: 7, status: 'accepted', screen: 'appointment/7' }
```

**Started:**
```
Title: بدأ موعدك
Body: موعدك مع {DESIGNER_NAME} بدأ الآن
Data: { type: 'appointment', appointmentId: 7, status: 'started', screen: 'appointment/7' }
```

## Deep Linking

When a user taps a notification, they are redirected to:
- **Orders:** `/orders/[id]` - Order details screen
- **Appointments:** `/appointment/[id]` - Appointment details screen

### Implementation

The notification data includes a `screen` field with the exact route to navigate to:

```typescript
// In useHybridNotifications.ts
const handleNotificationTap = (data: any) => {
  const { type, orderId, appointmentId, screen } = data;
  
  if (screen) {
    router.push(screen); // e.g., "orders/5" or "appointment/7"
  } else if (type === 'order' && orderId) {
    router.push(`/orders/${orderId}`);
  } else if (type === 'appointment' && appointmentId) {
    router.push(`/appointment/${appointmentId}`);
  }
};
```

## Testing

### 1. Register Expo Push Token

**For Guests (No Auth):**
```bash
POST http://your-api.com/api/expo-push/register-guest
Content-Type: application/json

{
  "token": "ExponentPushToken[xxxxx]",
  "device_id": "ios_12345",
  "platform": "ios"
}
```

**For Authenticated Users:**
```bash
POST http://your-api.com/api/expo-push/register
Authorization: Bearer {token}
Content-Type: application/json

{
  "token": "ExponentPushToken[xxxxx]",
  "device_id": "ios_12345",
  "platform": "ios"
}
```

### 2. Test Order Status Change

1. Login to admin panel
2. Go to Orders
3. Select an order
4. Change status from "pending" to "confirmed"
5. User should receive both Expo Push notification and WhatsApp message

### 3. Test Appointment Status Change

**Accept Appointment (Designer):**
1. Login as designer
2. Go to pending appointments
3. Accept an appointment
4. User receives notification

**Start Appointment (Designer):**
1. Login as designer
2. Go to accepted appointment
3. Click "Start Meeting"
4. User receives notification with meeting link

### 4. Test Deep Linking

**Test Notification Tap (App in Background):**
1. Send a test notification from admin
2. App is in background
3. Tap notification
4. Should navigate to order/appointment screen

**Test Notification Tap (App Killed):**
1. Force close the app
2. Send notification
3. Tap notification to open app
4. Should navigate to order/appointment screen after app loads

### 5. Debug Endpoints

**View All Tokens:**
```bash
GET http://your-api.com/api/debug/notifications
```

**Send Test Notification:**
```bash
POST http://your-api.com/api/test-notification-guest
```

## Configuration

### Expo Push Notifications

Expo Push Notifications work out of the box. No configuration needed.

### WhatsApp Integration

Update `.env`:
```env
WHATSAPP_ACCESS_TOKEN=your_smartwats_access_token
WHATSAPP_INSTANCE_ID=your_instance_id
WHATSAPP_BASE_URL=https://app.smartwats.com/api
WHATSAPP_TIMEOUT=30
```

## User Notification Preferences

Users can control WhatsApp notifications via their profile settings:

```php
// In users table
whatsapp_notifications: boolean (default: true)
```

## Error Handling

- All notification sending is wrapped in try-catch blocks
- Failures are logged but don't break the main flow
- Users still see success messages even if notifications fail
- Logs include detailed error information for debugging

## Logs

Check Laravel logs for notification activity:

```php
// Success logs
Log::info('Expo push notification sent successfully', [
    'user_id' => $user->id,
    'tokens_count' => count($tokens),
    'sent' => $result['data']['total_sent'],
]);

// Error logs
Log::error('Failed to send order status notification', [
    'order_id' => $order->id,
    'error' => $e->getMessage()
]);
```

## Common Issues

### 1. Notifications Not Received

**Check:**
- Token is registered in database (`expo_push_tokens` table)
- Token is active (`is_active = true`)
- User has granted notification permissions in app
- Expo project ID matches in app.json

### 2. Deep Linking Not Working

**Check:**
- Notification data includes `screen` field
- Routes exist in app (e.g., `/orders/[id]`)
- useRouter is properly imported from expo-router

### 3. WhatsApp Not Sending

**Check:**
- WhatsApp credentials in .env
- User has phone number in database
- User has `whatsapp_notifications = true`
- SmartWats API is accessible

## Production Checklist

- [ ] Expo project ID configured in app.json
- [ ] Push notification permissions requested on app start
- [ ] Token registration on login/signup
- [ ] Token cleanup on logout
- [ ] WhatsApp credentials configured
- [ ] Error monitoring setup
- [ ] Notification content reviewed and translated
- [ ] Deep linking routes tested
- [ ] Notification delivery tested on both iOS and Android

## API Reference

### Register Guest Token
```
POST /api/expo-push/register-guest
Body: { token, device_id, platform }
Response: { success, message, data }
```

### Register Authenticated Token
```
POST /api/expo-push/register
Headers: { Authorization: Bearer {token} }
Body: { token, device_id, platform }
Response: { success, message, data }
```

### Get User Tokens
```
GET /api/expo-push/tokens
Headers: { Authorization: Bearer {token} }
Response: { success, data: [] }
```

### Deactivate Token
```
POST /api/expo-push/deactivate
Headers: { Authorization: Bearer {token} }
Body: { token }
Response: { success, message }
```

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Expo logs in React Native app console
3. Test with debug endpoints
4. Review notification permissions in device settings

