# Online Server Payment Fixes

## Issues Identified

### 1. React Native Redirecting to Website
**Problem**: React Native app redirects to Laravel website instead of staying in app
**Cause**: Payment URL configuration issues
**Solution**: Fixed in OrderController.php

### 2. Order Status Being Cancelled
**Problem**: Orders are being set to "cancelled" status
**Cause**: Payment failures or webhook processing
**Solution**: Check payment validation and webhook handling

### 3. Webhook Not Working
**Problem**: Webhooks not being processed on online server
**Cause**: Server configuration or URL issues
**Solution**: Verify webhook endpoint accessibility

## Required Online Server Configuration

### 1. Environment Variables (.env)
```env
APP_URL=https://qaads.net
TAP_TEST_MODE=true
TAP_TEST_SECRET_KEY=sk_test_your_test_key
TAP_LIVE_SECRET_KEY=sk_live_your_live_key
TAP_PUBLIC_KEY=pk_test_your_public_key
```

### 2. Webhook URL in Tap Dashboard
Set webhook URL to: `https://qaads.net/api/webhooks/tap`

### 3. React Native App Configuration
- API Base URL: `https://qaads.net/api` ✅ (Already configured)
- Payment URLs: Will use `https://qaads.net/payment/success` ✅

## Fixed Issues

### 1. OrderController.php - Fixed Redirect URL
```php
'redirect' => [
    'url' => config('app.url') . '/payment/success?source=mobile&test_mode=' . (config('services.tap.test_mode', true) ? 'true' : 'false')
],
```

### 2. React Native WebView - Enhanced Error Handling
- Better error detection
- Specific error messages
- Proper success/failure URL patterns

### 3. Success Pages - Mobile JSON Support
- Returns JSON for mobile apps
- Returns HTML for web browsers
- Test mode indicators

## Testing Checklist

### ✅ Local Testing (Working)
- [x] API endpoints working
- [x] Payment charge creation working
- [x] Success pages working
- [x] Error handling working

### 🔧 Online Server Testing (Need to Verify)
- [ ] Webhook endpoint accessible: `https://qaads.net/api/webhooks/tap`
- [ ] Success pages working: `https://qaads.net/payment/success`
- [ ] Error pages working: `https://qaads.net/payment/error`
- [ ] React Native app connecting to: `https://qaads.net/api`

## Debugging Steps

### 1. Check Online Server Logs
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check webhook processing
grep "webhook" storage/logs/laravel.log
```

### 2. Test Webhook Endpoint
```bash
curl -X POST https://qaads.net/api/webhooks/tap \
  -H "Content-Type: application/json" \
  -d '{"test": "webhook"}'
```

### 3. Test Success Pages
- Visit: `https://qaads.net/payment/success?source=mobile&test_mode=true`
- Should return JSON for mobile apps
- Should return HTML for web browsers

### 4. Check Order Status Flow
- Orders start as "pending"
- Auto-confirmed to "confirmed" when payment initiated
- Set to "processing" when payment succeeds
- Set to "cancelled" when payment fails

## Common Issues & Solutions

### Issue: Order Status "cancelled"
**Possible Causes**:
1. Payment validation failed (invalid total, missing data)
2. Tap API returned error
3. Webhook processed failure status
4. Order validation rules failed

**Debug Steps**:
1. Check Laravel logs for payment errors
2. Verify order total is > 0
3. Check Tap API response
4. Verify webhook processing

### Issue: React Native Redirects to Website
**Possible Causes**:
1. Payment URL is web route instead of API route
2. WebView not handling redirects properly
3. Success URL detection not working

**Debug Steps**:
1. Check payment URL in API response
2. Verify WebView URL patterns
3. Test success URL detection

### Issue: Webhook Not Working
**Possible Causes**:
1. Webhook URL not accessible from internet
2. HTTPS certificate issues
3. Server firewall blocking requests
4. Tap dashboard webhook URL misconfigured

**Debug Steps**:
1. Test webhook endpoint accessibility
2. Check server logs for webhook requests
3. Verify Tap dashboard configuration
4. Test with ngrok for temporary access

## Production Deployment Checklist

### 1. Server Configuration
- [ ] HTTPS certificate installed
- [ ] Webhook endpoint accessible
- [ ] Laravel logs writable
- [ ] Environment variables set

### 2. Tap Dashboard Configuration
- [ ] Webhook URL set to: `https://qaads.net/api/webhooks/tap`
- [ ] Live API keys configured
- [ ] Test mode disabled for production

### 3. React Native App
- [ ] API URL points to production server
- [ ] Test mode disabled
- [ ] Error handling tested
- [ ] Success/failure flows tested

### 4. Testing
- [ ] Test with real cards (small amounts)
- [ ] Verify webhook processing
- [ ] Test success/failure scenarios
- [ ] Check order status updates
- [ ] Verify React Native app flow

## Support

If issues persist:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Tap dashboard for payment status
3. Test webhook endpoint accessibility
4. Verify environment configuration
5. Test with official Tap test cards
