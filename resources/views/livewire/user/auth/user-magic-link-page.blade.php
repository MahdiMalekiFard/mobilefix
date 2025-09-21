<div>
    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="auth-card">
                        <div class="text-center mb-4">
                            <h2 class="auth-title">
                                @if($isValid && !$isExpired)
                                    @if($user)
                                        Willkommen zurück!
                                    @else
                                        Konto erstellen
                                    @endif
                                @else
                                    Ungültiger oder abgelaufener Link
                                @endif
                            </h2>
                        </div>

                        @if($isValid && !$isExpired)
                            @if($user)
                                <!-- User exists - Auto login -->
                                <div class="text-center">
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Sie werden automatisch angemeldet...
                                    </div>
                                    <p class="text-muted">Sie werden in Kürze zu Ihrem Dashboard weitergeleitet.</p>
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Lädt...</span>
                                    </div>
                                </div>
                            @else
                                <!-- User doesn't exist - Create account -->
                                <div class="text-center mb-4">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Kein Konto für <strong>{{ $email }}</strong> gefunden
                                    </div>
                                    <p class="text-muted">Möchten Sie ein Konto erstellen, um Ihre Reparaturaufträge zu verfolgen?</p>
                                    
                                    @if($existingOrdersCount > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-link me-2"></i>
                                            <strong>{{ $existingOrdersCount }}</strong> Reparaturanfrage(n) werden automatisch mit Ihrem neuen Konto verknüpft!
                                        </div>
                                    @endif
                                </div>

                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-user me-2"></i>
                                            Kontodetails
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Name:</strong> {{ $orderInfo['name'] ?? 'Benutzer' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Email:</strong> {{ $email }}
                                            </div>
                                            @if($orderInfo['mobile'] ?? null)
                                            <div class="col-md-6">
                                                <strong>Telefon:</strong> {{ $orderInfo['mobile'] }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-3">
                                    <button wire:click="createAccount" class="btn btn-primary btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Konto erstellen & fortfahren
                                    </button>
                                    
                                    <a href="{{ route('user.auth.login') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Mit anderem Konto anmelden
                                    </a>
                                </div>

                                <div class="mt-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-shield-alt me-2"></i>
                                                Was passiert, wenn Sie ein Konto erstellen?
                                            </h6>
                                            <ul class="list-unstyled mb-0">
                                                <li><i class="fas fa-check text-success me-2"></i>Zugang zu Ihren Reparaturaufträgen</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Reparaturfortschritt online verfolgen</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Status-Updates erhalten</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Ihr Profil verwalten</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Ihre bestehenden Reparaturanfragen werden automatisch verknüpft</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Invalid or expired link -->
                            <div class="text-center">
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Dieser Link ist ungültig oder abgelaufen
                                </div>
                                <p class="text-muted mb-4">
                                    Der verwendete Magic-Link ist nicht mehr gültig. Das könnte folgende Gründe haben:
                                </p>
                                <ul class="text-start text-muted">
                                    <li>Der Link ist abgelaufen (Links laufen nach 24 Stunden ab)</li>
                                    <li>Der Link wurde bereits verwendet</li>
                                    <li>Der Link ist ungültig</li>
                                </ul>
                                
                                <div class="mt-4">
                                    <a href="{{ route('user.auth.login') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Bei Ihrem Konto anmelden
                                    </a>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-muted">
                                        Hilfe benötigt? Kontaktieren Sie unser Support-Team mit Ihrem Tracking-Code.
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem 0;
    }

    .auth-card {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .auth-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    </style>
</div>
