<x-mail::message>
# Your Device Repair Request Was Received

Thanks for submitting your device for repair. Here's a summary:

<x-mail::panel>
- **Name:** {{ $payload['name'] ?? '—' }}
- **Email:** {{ $payload['email'] ?? '—' }}
- **Phone:** {{ $payload['phone'] ?? '—' }}
- **Brand:** {{ $payload['brand'] ?? '—' }}
- **Model:** {{ $payload['model'] ?? '—' }}
- **Problems:** {{ collect($payload['problems'] ?? [])->join(', ', ' and ') ?: '—' }}
</x-mail::panel>

**Description**
> {{ $payload['description'] ?? '—' }}

<x-mail::panel>
**🔍 Your Tracking Code: {{ $payload['tracking_code'] ?? '—' }}**
</x-mail::panel>

We'll update you soon with the next steps.

<x-mail::button :url="$payload['magic_link'] ?? route('user.auth.login')">
🚀 Access Your Dashboard
</x-mail::button>

**Important Information:**
- **Tracking Code:** {{ $payload['tracking_code'] ?? '—' }}
- **Email:** {{ $payload['email'] ?? '—' }}
- **Order ID:** {{ $payload['order_id'] ?? '—' }}

**How to Track Your Order:**
@if($payload['is_authenticated'] ?? false)
1. **Click the button above** to access your dashboard and view your orders
2. **Your order is linked to your account** - you can track progress anytime
@else
1. **Click the button above** to automatically login or create an account
2. **Magic link access** - No password needed, just click the button
3. **For immediate support and updates**, contact our support team with your tracking code: {{ $payload['tracking_code'] ?? '—' }}
4. **Keep this email safe** - it contains all the information you need to track your repair
5. **Want to track online?** Contact us to link this order to your account if you create one later
@endif

**Keep this tracking code safe** - you'll need it for pickup and status inquiries.

**Support Contact:**
If you have any questions, please contact our support team with your tracking code: {{ $payload['tracking_code'] ?? '—' }}

**Next Steps:**
1. **Save this email** - it contains your tracking information
2. **Check your spam folder** if you don't see this email
3. **We'll contact you within 24 hours** to confirm repair details
4. **Keep your tracking code handy** for all future communications

**Need Help?**
- **Email:** support@{{ config('app.name') }}.com
- **Phone:** Contact our support team
- **Reference:** Always mention your tracking code: {{ $payload['tracking_code'] ?? '—' }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
