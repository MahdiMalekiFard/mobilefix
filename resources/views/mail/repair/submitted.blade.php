<x-mail::message>
    # Ihr Reparaturauftrag wurde erhalten

    Vielen Dank, dass Sie Ihr Gerät zur Reparatur eingereicht haben. Hier eine Zusammenfassung:

    <x-mail::panel>
        - **Name:** {{ $payload['name'] ?? '—' }}
        - **E-Mail:** {{ $payload['email'] ?? '—' }}
        - **Telefon:** {{ $payload['phone'] ?? '—' }}
        - **Marke:** {{ $payload['brand'] ?? '—' }}
        - **Modell:** {{ $payload['model'] ?? '—' }}
        - **Probleme:** {{ collect($payload['problems'] ?? [])->join(', ', ' und ') ?: '—' }}
    </x-mail::panel>

    **Beschreibung**
    > {{ $payload['description'] ?? '—' }}

    <x-mail::panel>
        **🔍 Ihr Tracking-Code: {{ $payload['tracking_code'] ?? '—' }}**
    </x-mail::panel>

    Wir informieren Sie in Kürze über die nächsten Schritte.

    <x-mail::button :url="$payload['magic_link'] ?? route('user.auth.login')">
        🚀 Zum Dashboard
    </x-mail::button>

    **Wichtige Informationen:**
    - **Tracking-Code:** {{ $payload['tracking_code'] ?? '—' }}
    - **E-Mail:** {{ $payload['email'] ?? '—' }}
    - **Bestellnummer:** {{ $payload['order_id'] ?? '—' }}

    **So verfolgen Sie Ihren Auftrag:**
    @if($payload['is_authenticated'] ?? false)
        1. **Klicken Sie auf den Button oben**, um Ihr Dashboard zu öffnen und Ihre Aufträge einzusehen
        2. **Ihre Bestellung ist mit Ihrem Konto verknüpft** – Sie können den Fortschritt jederzeit verfolgen
    @else
        1. **Klicken Sie auf den Button oben**, um sich automatisch einzuloggen oder ein Konto zu erstellen
        2. **Magic-Link-Zugang** – kein Passwort erforderlich, einfach den Button klicken
        3. **Für sofortige Unterstützung und Updates** kontaktieren Sie unser Support-Team mit Ihrem Tracking-Code: {{ $payload['tracking_code'] ?? '—' }}
        4. **Bewahren Sie diese E-Mail gut auf** – sie enthält alle Informationen zur Nachverfolgung Ihrer Reparatur
        5. **Online nachverfolgen?** Kontaktieren Sie uns, um diesen Auftrag mit Ihrem Konto zu verknüpfen, falls Sie später eines erstellen
    @endif

    **Bewahren Sie diesen Tracking-Code gut auf** – Sie benötigen ihn für Abholung und Statusanfragen.

    **Support-Kontakt:**
    Wenn Sie Fragen haben, kontaktieren Sie bitte unser Support-Team mit Ihrem Tracking-Code: {{ $payload['tracking_code'] ?? '—' }}

    **Nächste Schritte:**
    1. **Speichern Sie diese E-Mail** – sie enthält Ihre Tracking-Informationen
    2. **Überprüfen Sie Ihren Spam-Ordner**, falls Sie diese E-Mail nicht sehen
    3. **Wir melden uns innerhalb von 24 Stunden**, um die Reparaturdetails zu bestätigen
    4. **Halten Sie Ihren Tracking-Code bereit** für alle zukünftigen Rückfragen

    **Brauchen Sie Hilfe?**
    - **E-Mail:** support@{{ config('app.name') }}.com
    - **Telefon:** Kontaktieren Sie unser Support-Team
    - **Referenz:** Geben Sie immer Ihren Tracking-Code an: {{ $payload['tracking_code'] ?? '—' }}

    Vielen Dank,<br>
    {{ config('app.name') }}
</x-mail::message>
