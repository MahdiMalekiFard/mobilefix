<div>
    <!-- login area -->
    <div class="login-area d-flex min-vh-100">
        <!-- Left Side - Vector Illustration -->
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg,rgb(167, 172, 188) 0%,rgb(70, 114, 157) 100%);">
            <div class="text-center text-white p-5">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" class="mb-3">
                <h2 class="mt-4 mb-3">Mobiler Reparaturservice</h2>
                <p class="lead">Professionelle Reparatur- und Wartungsdienste für Mobilgeräte</p>
                <a href="{{ route('home-page') }}" class="btn btn-light mt-4">
                    <i class="fas fa-home me-2"></i>
                    <span class="fw-bold">Zurück zur Website</span>
                </a>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-100" style="max-width: 550px;">
                <div class="login-form bg-white p-5 rounded shadow" style="padding: 3rem 4rem !important;">
                    <div class="login-header text-center mb-4">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" class="mb-3">
                        <p class="text-muted">Beim Benutzer-Dashboard anmelden</p>
                    </div>
                    <form wire:submit="login" novalidate>
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">E-Mail-Adresse</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Ihre E-Mail" required wire:model.live="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Passwort</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ihr Passwort" required wire:model.live="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between mb-4">
{{--                            <a href="forgot-password.html" class="forgot-pass">Passwort vergessen?</a>--}}
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="theme-btn btn-lg">
                                <i class="far fa-sign-in"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- login area end -->
</div>
