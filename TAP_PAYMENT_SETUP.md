# Tap Payment Gateway Integration Setup

This document provides instructions for setting up and testing the Tap Payment Gateway integration in your Laravel application.

## 🚀 Quick Start

### 1. Environment Configuration

Add the following environment variables to your `.env` file:

```env
# Tap Payment Gateway Configuration
# Get your API keys from: https://dashboard.tap.company/

# Tap API Keys (Test Mode)
TAP_SECRET_KEY=
TAP_PUBLIC_KEY=
TAP_TEST_MODE=true

# Tap API Keys (Live Mode) - Uncomment and use these for production
# TAP_SECRET_KEY=
# TAP_PUBLIC_KEY=
# TAP_TEST_MODE=false

# Webhook Secret (Optional - for webhook signature verification)
TAP_WEBHOOK_SECRET=your_webhook_secret_here
```

### 2. Get Your API Keys

1. Visit [Tap Dashboard](https://dashboard.tap.company/)
2. Sign up or log in to your account
3. Navigate to **goSell → API Credentials → Generate Key**
4. Copy your test and live API keys
5. Update your `.env` file with the actual keys

### 3. Test the Integration

1. Start your Laravel development server:
   ```bash
   php artisan serve
   ```

2. Visit the test page: `http://localhost:8000/payment-test`

3. Use the test card numbers provided on the page to simulate payments

## 📋 Test Card Numbers

The following test card numbers are available for testing:

### Visa
- **Number:** 4242424242424242
- **CVV:** 100
- **Expiry:** 05/2025

### Mastercard
- **Number:** 5555555555554444
- **CVV:** 100
- **Expiry:** 05/2025

### American Express
- **Number:** 378282246310005
- **CVV:** 1000
- **Expiry:** 05/2025

## 🔧 API Endpoints

### Payment Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/payments/create-charge` | Create a payment charge |
| GET | `/api/payments/status` | Get payment status by charge ID |
| POST | `/api/payments/refund` | Create a refund |
| GET | `/api/payments/test-cards` | Get test card numbers |

### Webhook Endpoint

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/webhooks/tap` | Handle Tap webhook notifications |

### Success/Cancel URLs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payment/success` | Payment success page |
| GET | `/payment/cancel` | Payment cancellation page |

## 💻 Usage Examples

### Create a Payment Charge

```javascript
const response = await fetch('/api/payments/create-charge', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        order_id: 1,
        amount: 10.00,
        currency: 'KWD',
        customer: {
            first_name: 'John',
            last_name: 'Doe',
            email: 'john@example.com',
            phone: '+96512345678'
        },
        redirect_url: 'https://yoursite.com/payment/success',
        post_url: 'https://yoursite.com/api/webhooks/tap'
    })
});

const result = await response.json();
if (result.success) {
    // Redirect user to payment URL
    window.location.href = result.data.payment_url;
}
```

### Check Payment Status

```javascript
const response = await fetch('/api/payments/status?charge_id=chg_123456789');
const result = await response.json();

if (result.success) {
    console.log('Payment Status:', result.data.status);
    console.log('Amount:', result.data.amount, result.data.currency);
}
```

### Create a Refund

```javascript
const response = await fetch('/api/payments/refund', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        charge_id: 'chg_123456789',
        amount: 5.00,
        reason: 'Customer request'
    })
});

const result = await response.json();
```

## 🔄 Webhook Handling

The webhook endpoint automatically handles payment status updates from Tap. When a payment status changes, the system will:

1. Verify the webhook signature
2. Update the payment record in the database
3. Update the associated order status
4. Log the webhook event

### Webhook Events

- `CAPTURED` → Payment completed successfully
- `FAILED` → Payment failed
- `CANCELLED` → Payment was cancelled

## 🗄️ Database Schema

### Payments Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| order_id | bigint | Foreign key to orders table |
| charge_id | string | Tap charge ID (unique) |
| amount | decimal(10,2) | Payment amount |
| currency | string(3) | Currency code (KWD, USD, etc.) |
| status | enum | Payment status (pending, completed, failed, cancelled, refunded) |
| payment_method | string | Payment method (default: 'tap') |
| gateway_response | json | Full response from Tap API |
| transaction_id | string | Transaction ID (nullable) |
| paid_at | timestamp | When payment was completed (nullable) |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record update time |

## 🛡️ Security Considerations

1. **API Keys**: Never commit your live API keys to version control
2. **Webhook Verification**: Always verify webhook signatures in production
3. **HTTPS**: Use HTTPS for all payment-related endpoints in production
4. **Input Validation**: All inputs are validated before processing
5. **Error Handling**: Sensitive information is not exposed in error messages

## 🚨 Production Checklist

Before going live:

- [ ] Replace test API keys with live keys
- [ ] Set `TAP_TEST_MODE=false`
- [ ] Configure webhook URLs in Tap dashboard
- [ ] Set up webhook secret for signature verification
- [ ] Test with real payment methods
- [ ] Set up monitoring and logging
- [ ] Configure proper error handling
- [ ] Review security measures

## 📞 Support

For issues with the Tap Payment integration:

1. Check the Laravel logs: `storage/logs/laravel.log`
2. Review the Tap API documentation: https://developers.tap.company/
3. Contact Tap support through their dashboard
4. Check the test page for debugging: `/payment-test`

## 🔗 Useful Links

- [Tap API Documentation](https://developers.tap.company/)
- [Tap Dashboard](https://dashboard.tap.company/)
- [Test Cards](https://developers.tap.company/reference/test-cards-numbers)
- [Webhook Guide](https://developers.tap.company/reference/webhook)
