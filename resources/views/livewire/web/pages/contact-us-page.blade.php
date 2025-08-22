<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url(assets/images/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Kontaktieren Sie uns</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Kontaktieren Sie uns</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- contact area -->
    <div class="contact-area py-120">
        <div class="container">
            <div class="contact-wrap">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="contact-content">
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-location-dot"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Büroadresse</h5>
                                    <p>Friedrichstraße 123 10117 Berlin Deutschland</p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-phone-volume"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Rufen Sie uns an</h5>
                                    <p>+49 7648 9939</p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-envelope"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>E-Mail schreiben</h5>
                                    <p>info@Fix-mobil.de</p>
                                </div>
                            </div>
                            <div class="contact-info border-0">
                                <div class="contact-info-icon">
                                    <i class="fal fa-clock"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Offene Zeit</h5>
                                    <p>Montag - Samstag (10.00AM - 05.30PM)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="contact-form">
                            <div class="contact-form-header">
                                <h2>Kontaktieren Sie uns</h2>
                                <p>
                                    Egal ob Sie eine Frage zu unseren Reparaturleistungen haben,
                                    Hilfe bei einem Problem benötigen oder einen Termin vereinbaren möchten, unser Team hilft Ihnen gerne weiter.
                                </p>
                            </div>

                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Schließen"></button>
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Schließen"></button>
                                </div>
                            @endif

                            <form wire:submit="submitForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                wire:model.live="name" placeholder="Ihr Name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                wire:model.live="email" placeholder="Ihre E-Mail">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                        wire:model.live="subject" placeholder="Ihr Betreff">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        wire:model.live="phone" placeholder="Ihre Telefonnummer">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <textarea wire:model.live="message" cols="30" rows="5" class="form-control @error('message') is-invalid @enderror"
                                        placeholder="Schreiben Sie Ihre Nachricht"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="theme-btn" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Nachricht senden <i class="far fa-paper-plane"></i></span>
                                    <span wire:loading>Senden... <i class="fas fa-spinner fa-spin"></i></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end contact area -->

    <!-- map -->
    <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d96708.34194156103!2d-74.03927096447748!3d40.759040329405195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4a01c8df6fb3cb8!2sSolomon%20R.%20Guggenheim%20Museum!5e0!3m2!1sen!2sbd!4v1619410634508!5m2!1sen!2s"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

