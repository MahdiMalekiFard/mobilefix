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
                                        Welcome Back!
                                    @else
                                        Create Your Account
                                    @endif
                                @else
                                    Invalid or Expired Link
                                @endif
                            </h2>
                        </div>

                        @if($isValid && !$isExpired)
                            @if($user)
                                <!-- User exists - Auto login -->
                                <div class="text-center">
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Logging you in automatically...
                                    </div>
                                    <p class="text-muted">You will be redirected to your dashboard shortly.</p>
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            @else
                                <!-- User doesn't exist - Create account -->
                                <div class="text-center mb-4">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No account found for <strong>{{ $email }}</strong>
                                    </div>
                                    <p class="text-muted">Would you like to create an account to track your repair orders?</p>
                                    
                                    @if($existingOrdersCount > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-link me-2"></i>
                                            <strong>{{ $existingOrdersCount }}</strong> repair request(s) will be automatically linked to your new account!
                                        </div>
                                    @endif
                                </div>

                                <div class="d-grid gap-3">
                                    <button wire:click="createAccount" class="btn btn-primary btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Create Account & Continue
                                    </button>
                                    
                                    <a href="{{ route('user.auth.login') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login with Different Account
                                    </a>
                                </div>

                                <div class="mt-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-shield-alt me-2"></i>
                                                What happens when you create an account?
                                            </h6>
                                            <ul class="list-unstyled mb-0">
                                                <li><i class="fas fa-check text-success me-2"></i>Access to your repair orders</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Track repair progress online</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Receive status updates</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Manage your profile</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Your existing repair requests will be automatically linked</li>
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
                                    This link is invalid or has expired
                                </div>
                                <p class="text-muted mb-4">
                                    The magic link you used is no longer valid. This could be because:
                                </p>
                                <ul class="text-start text-muted">
                                    <li>The link has expired (links expire after 24 hours)</li>
                                    <li>The link has already been used</li>
                                    <li>The link is invalid</li>
                                </ul>
                                
                                <div class="mt-4">
                                    <a href="{{ route('user.auth.login') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login to Your Account
                                    </a>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-muted">
                                        Need help? Contact our support team with your tracking code.
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
