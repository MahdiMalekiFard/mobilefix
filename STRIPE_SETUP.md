# Stripe Payment Integration Setup

This document explains how to set up and use the Stripe payment integration for the UserOrderPay component.

## Environment Configuration

Add the following environment variables to your `.env` file:

```env
# Stripe Keys (Get these from your Stripe Dashboard)
STRIPE_PUBLIC=pk_test_51...  # Your Stripe publishable key
STRIPE_SECRET=sk_test_51...  # Your Stripe secret key
STRIPE_WEBHOOK_SECRET=whsec_... # Your webhook endpoint secret

# Stripe Settings
STRIPE_CURRENCY=usd  # Currency code (usd, eur, etc.)
```

## Stripe Dashboard Configuration

1. **Get your API keys:**
   - Log in to your Stripe Dashboard
   - Go to Developers > API keys
   - Copy your Publishable key and Secret key

2. **Set up webhooks:**
   - Go to Developers > Webhooks
   - Click "Add endpoint"
   - URL: `https://yourdomain.com/stripe/webhook`
   - Select events to send:
     - `payment_intent.succeeded`
     - `payment_intent.payment_failed`
     - `payment_intent.canceled`
     - `payment_intent.requires_action`
     - `payment_method.attached`
   - Copy the webhook signing secret

## Usage

### Basic Usage

```php
// In your route or controller
Route::get('/order/{order}/pay', UserOrderPay::class)->name('user.order.pay');
```

### Frontend Usage

The component will automatically:
1. Load the order and validate user permissions
2. Create a Stripe PaymentIntent
3. Display the payment form with Stripe Elements
4. Handle payment success/failure
5. Update order status accordingly

### Order Status Flow

- `pending` → `paid` (on successful payment)
- `pending` → `failed` (on payment failure)
- `completed` → `paid` (on successful payment for completed orders)

## Security Features

- CSRF protection is disabled only for the webhook endpoint
- Webhook signature verification ensures authenticity
- User authorization checks prevent unauthorized payments
- Payment intent validation prevents duplicate payments

## Testing

For testing, use Stripe's test card numbers:
- Success: `4242424242424242`
- Decline: `4000000000000002`
- 3D Secure: `4000002760003184`

## Troubleshooting

1. **Payment not processing:** Check your Stripe keys in `.env`
2. **Webhook not working:** Verify webhook secret and URL configuration
3. **Order not updating:** Check Laravel logs for webhook processing errors
4. **Frontend errors:** Check browser console for JavaScript errors

## File Structure

```
app/
├── Services/Payment/StripeService.php          # Stripe API service
├── Livewire/User/Pages/Order/UserOrderPay.php  # Payment component
├── Http/Controllers/WebhookController.php      # Webhook handler
resources/views/livewire/user/pages/order/
└── user-order-pay.blade.php                   # Payment view
```

## Features

- ✅ Secure payment processing with Stripe Elements
- ✅ Real-time payment status updates
- ✅ Webhook handling for reliable payment confirmation
- ✅ User-friendly payment interface
- ✅ Error handling and retry functionality
- ✅ Order summary display
- ✅ Mobile-responsive design
- ✅ 3D Secure authentication support
