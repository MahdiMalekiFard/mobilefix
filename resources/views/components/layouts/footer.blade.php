<!-- footer area -->
<footer class="footer-area footer-bg">
        <div class="footer-widget">
            <div class="container">
                <div class="row footer-widget-wrap pt-100 pb-70">
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-widget-box about-us">
                            <a href="#" class="footer-logo">
                                <img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="">
                            </a>
                            <p class="mb-4">
                                Willkommen bei Mobile Fix, Ihrem vertrauenswürdigen Ziel für schnelle,
                                zuverlässige und kostengünstige Reparaturdienste für Mobiltelefone.
                            </p>
                            <ul class="footer-contact">
                                <li><a href="tel:+4976489939"><i class="far fa-phone"></i>+49 7648 9939</a></li>
                                <li><i class="far fa-map-marker-alt"></i>Friedrichstraße 123 10117 Berlin Deutschland</li>
                                <li><a href="mailto:info@Fix-mobil.de"><i
                                            class="far fa-envelope"></i>info@Fix-mobil.de</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Direktlinks</h4>
                            <ul class="footer-list">
                                <li><a href="{{ route('about-us-page') }}"><i class="fas fa-dot-circle"></i> Über uns</a></li>
                                <li><a href="{{ route('faq-page') }}"><i class="fas fa-dot-circle"></i> FAQ</a></li>
                                <li><a href="{{ route('terms-page') }}"><i class="fas fa-dot-circle"></i> Servicebedingungen</a></li>
                                <li><a href="{{ route('privacy-page') }}"><i class="fas fa-dot-circle"></i> Datenschutzrichtlinie</a></li>
                                <li><a href="{{ route('team-page') }}"><i class="fas fa-dot-circle"></i> Unser Team</a></li>
                                <li><a href="{{ route('blog-page') }}"><i class="fas fa-dot-circle"></i> Neuester Blog</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Unsere Dienste</h4>
                            <ul class="footer-list">
                                @foreach(\App\Models\Service::where('published', \App\Enums\BooleanEnum::ENABLE)->get() as $service)
                                    <li><a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}"><i class="fas fa-dot-circle"></i> {{ $service?->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Zahlungsarten</h4>
                            <div class="footer-newsletter">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <img src="{{ asset('assets/images/payments/stripe.png') }}"
                                        alt="Stripe"
                                        class="img-fluid"
                                        style="max-height: 40px; width: auto;">
                                    <img src="{{ asset('assets/images/payments/paypal.png') }}"
                                        alt="PayPal"
                                        class="img-fluid"
                                        style="max-height: 40px; width: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <p class="copyright-text">
                            &copy; Copyright <span id="date"></span> <a href="{{ route('home-page') }}"> Fixmobil </a> Alle Rechte vorbehalten.
                        </p>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="footer-social">
                            <li><a href="https://www.facebook.com/#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://x.com/#"><i class="fab fa-x-twitter"></i></a></li>
                            <li><a href="https://www.linkedin.com/#"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="https://www.youtube.com/#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer area end -->
