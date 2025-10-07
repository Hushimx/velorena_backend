# Tap Payment Gateway Setup Guide

## Environment Configuration

### Required Environment Variables

Add these variables to your `.env` file:

```env
# Tap Payment Configuration
TAP_TEST_MODE=true
TAP_TEST_SECRET_KEY=sk_test_your_test_secret_key_here
TAP_LIVE_SECRET_KEY=sk_live_your_live_secret_key_here
TAP_PUBLIC_KEY=pk_test_your_public_key_here

# Legacy (for backward compatibility)
TAP_SECRET_KEY=sk_test_your_test_secret_key_here
```

### Test vs Production Mode

- **Test Mode (`TAP_TEST_MODE=true`)**: Uses test API keys, no real money is charged
- **Production Mode (`TAP_TEST_MODE=false`)**: Uses live API keys, real money is charged

## API Keys Setup

### Test Mode Keys
1. Log into your Tap Dashboard
2. Go to **Developers** → **API Keys**
3. Copy your **Test Secret Key** (starts with `sk_test_`)
4. Copy your **Test Public Key** (starts with `pk_test_`)

### Production Mode Keys
1. In your Tap Dashboard, switch to **Live Mode**
2. Go to **Developers** → **API Keys**
3. Copy your **Live Secret Key** (starts with `sk_live_`)
4. Copy your **Live Public Key** (starts with `pk_live_`)

## Webhook Configuration

### Webhook URL
Set your webhook URL in Tap Dashboard:
```
https://yourdomain.com/api/webhooks/tap
```

### Webhook Security
Tap doesn't use signature verification like other payment gateways. Instead, we verify webhook authenticity by:
1. Checking if the charge ID exists in our database
2. Validating the webhook data structure
3. Logging all webhook attempts for security monitoring

## Test Cards

### Visa
- **Number**: 4242424242424242
- **CVV**: 100
- **Expiry**: 05/2025

### Mastercard
- **Number**: 5555555555554444
- **CVV**: 100
- **Expiry**: 05/2025

### American Express
- **Number**: 378282246310005
- **CVV**: 1000
- **Expiry**: 05/2025

## Payment Flow

### React Native App
1. User initiates payment from checkout
2. App calls `/api/orders/{id}/payment`
3. Server creates Tap charge and returns payment URL
4. App opens WebView with payment URL
5. User completes payment on Tap's secure page
6. Tap redirects to `/payment/success?source=mobile&test_mode=true/false`
7. WebView detects success and shows success screen
8. App navigates back to orders list

### Web Checkout
1. User fills checkout form
2. Form submits to `/orders/{id}/process-payment`
3. Server creates Tap charge and redirects to payment URL
4. User completes payment on Tap's secure page
5. Tap redirects to `/payment/success?source=web&test_mode=true/false`
6. Success page shows confirmation

## Troubleshooting

### Common Issues

1. **"API key is not configured"**
   - Check that `TAP_TEST_SECRET_KEY` or `TAP_LIVE_SECRET_KEY` is set
   - Ensure the key starts with `sk_test_` or `sk_live_`

2. **Payment fails with "Invalid customer data"**
   - Ensure customer phone number is in correct format
   - Check that all required customer fields are provided

3. **Webhook not receiving notifications**
   - Verify webhook URL is accessible from internet
   - Check that webhook URL is correctly set in Tap Dashboard
   - Ensure webhook endpoint returns 200 status code
   - Check Laravel logs for webhook processing errors

4. **Test payments not working**
   - Verify `TAP_TEST_MODE=true` in `.env`
   - Use test API keys, not live keys
   - Use test card numbers provided above

### Debug Mode

Enable detailed logging by adding to `.env`:
```env
LOG_LEVEL=debug
```

Check logs in `storage/logs/laravel.log` for detailed payment flow information.

## Security Notes

- Never commit API keys to version control
- Use different keys for test and production environments
- Regularly rotate your API keys
- Monitor webhook endpoints for suspicious activity
- Use HTTPS for all webhook URLs
- Tap doesn't use webhook signatures - we verify authenticity by checking charge existence

## Support

For Tap-specific issues:
- Tap Documentation: https://docs.tap.company/
- Tap Support: support@tap.company

For application-specific issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Verify environment configuration
- Test with provided test cards first
