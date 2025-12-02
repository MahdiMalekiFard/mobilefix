@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
@endphp

@push('head')
    <style>
        /* Aspect ratio for about images */
        .about-img-1 img {
            aspect-ratio: 4 / 5;
            object-fit: cover;
        }
        .about-img-2 img {
            aspect-ratio: 3 / 4;
            object-fit: cover;
        }
    </style>
@endpush
<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url(assets/images/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Über uns</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Über uns</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- about area -->
    <div class="about-area py-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-left wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="about-img">
                            <div class="about-img-1">
                                <picture>
                                    <source srcset="{{ $page?->getFirstMediaUrl('images', Constants::RESOLUTION_854_480) }}" type="image/webp">
                                    <img src="{{ $page?->getFirstMediaUrl('images', Constants::RESOLUTION_854_480) }}" alt="About us" width="854" height="480" loading="lazy">
                                </picture>
                            </div>
                            <div class="about-img-2">
                                <picture>
                                    <source srcset="{{ $page?->getLastMediaUrl('images', Constants::RESOLUTION_854_480) }}" type="image/webp">
                                    <img src="{{ $page?->getLastMediaUrl('images', Constants::RESOLUTION_854_480) }}" alt="About us" width="854" height="480" loading="lazy">
                                </picture>
                            </div>
                        </div>
                        <div class="about-shape"><img src="{{ asset('assets/images/shape/01.png') }}" alt="" width="200" height="200" loading="lazy"></div>
                        <div class="about-experience">
                            <h1>25+</h1>
                            <div class="about-experience-text">
                                Jahre <br> Erfahrung
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-right wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Über uns</span>
                            <h2 class="site-title">
                                {{ $page?->title }}
                            </h2>
                        </div>
                        <p class="about-text">
                            {{ $page?->body }}
                        </p>
                        <div class="about-list-wrap">
                            <ul class="about-list list-unstyled">
                                <li>
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/icon/money.svg') }}" alt="" width="50" height="50" loading="lazy">
                                    </div>
                                    <div class="content">
                                        <h4>Unser günstiger Preis</h4>
                                        <p>Faire und transparente Preise – keine versteckten Kosten.
                                            Hochwertige Reparaturen müssen nicht teuer sein.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/icon/trusted.svg') }}" alt="" width="50" height="50" loading="lazy">
                                    </div>
                                    <div class="content">
                                        <h4>Vertrauenswürdiger Reparaturservice</h4>
                                        <p>Wir stehen für Ehrlichkeit, Zuverlässigkeit und Qualität.
                                            Tausende zufriedene Kunden vertrauen bereits unserem Service.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about area end -->


    <!-- counter area -->
    <div class="counter-area pt-100">
        <div class="container">
            <div class="counter-wrap">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="{{ asset('assets/images/icon/repair-2.svg') }}" alt="" width="50" height="50" loading="lazy">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="1200" data-speed="3000">1200</span>
                                <h6 class="title">+ alle Anfragen </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="{{ asset('assets/images/icon/happy.svg') }}" alt="" width="50" height="50" loading="lazy">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="1500" data-speed="3000">1500</span>
                                <h6 class="title">+ Erledigt</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="{{ asset('assets/images/icon/team-2.svg') }}" alt="" width="50" height="50" loading="lazy">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="400" data-speed="3000">400</span>
                                <h6 class="title">+ alle Benutzer</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="{{ asset('assets/images/icon/award.svg') }}" alt="" width="50" height="50" loading="lazy">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="80" data-speed="3000" data-suffix="%">80</span>
                                <h6 class="title">+ Zufriedenheit</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter area end -->


    <!-- choose area -->
    <div class="choose-area py-120">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="choose-content wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Warum Uns Wählen</span>
                            <h2 class="site-title">
                                Wenn Sie eine Reparatur benötigen <span>Wir sind</span> Immer hier
                            </h2>
                        </div>
                        <p>
                            Ob Displaybruch, Akkuwechsel oder andere Defekte – wir helfen sofort.
                            Unser Team ist jederzeit für Sie erreichbar und zuverlässig zur Stelle.
                        </p>
                        <div class="choose-wrapper mt-4">
                            <div class="choose-item">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/team-2.svg') }}" alt="" width="50" height="50" loading="lazy">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Qualifizierte Techniker</h4>
                                    <p>Unser Team besteht aus zertifizierten und erfahrenen Experten.
                                        Jedes Gerät wird mit größter Sorgfalt repariert.</p>
                                </div>
                            </div>
                            <div class="choose-item active">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/quality.svg') }}" alt="" width="50" height="50" loading="lazy">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Qualitätsgarantie</h4>
                                    <p>Wir verwenden ausschließlich hochwertige Ersatzteile.
                                        Auf jede Reparatur erhalten Sie unsere volle Garantie.</p>
                                </div>
                            </div>
                            <div class="choose-item">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/trusted.svg') }}" alt="" width="50" height="50" loading="lazy">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Ihr zuverlässiger Partner</h4>
                                    <p>Von der Beratung bis zur Reparatur stehen wir an Ihrer Seite.
                                        Vertrauen Sie auf unseren Service – schnell, fair und professionell.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="choose-img wow fadeInRight" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="row g-4">
                            <div class="col-6">
                                <img class="img-1" src="{{ asset('assets/images/choose/01.jpg') }}" alt="" width="400" height="500" loading="lazy">
                            </div>
                            <div class="col-6">
                                <img class="img-2" src="{{ asset('assets/images/choose/02.jpg') }}" alt="" width="400" height="500" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- choose area end -->


    <!-- testimonial-area -->
    <div class="testimonial-area py-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Kundenbewertungen</span>
                        <h2 class="site-title text-white">Welcher Kunde <span>Sagen Sie</span> Über uns</h2>
                        <div class="heading-divider"></div>
                    </div>
                </div>
            </div>
            <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                @foreach($opinions as $opinion)
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <picture>
                                    <source srcset="{{ $opinion?->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}" type="image/webp">
                                    <img src="{{ $opinion?->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}" alt="" width="100" height="100" loading="lazy">
                                </picture>
                            </div>
                            <div class="testimonial-author-info">
                                <h4>{{ $opinion?->user_name }}</h4>
                                <p>{{ $opinion?->company }}</p>
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <p>
                                {{ $opinion?->comment }}
                            </p>
                            <div class="testimonial-quote-icon">
                                <img src="{{ asset('assets/images/icon/quote.svg') }}" alt="" width="50" height="50" loading="lazy">
                            </div>
                        </div>
                        <div class="testimonial-rate">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- testimonial-area end -->


    <!-- team-area -->
    <div class="team-area bg pt-80 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Unser Team</span>
                        <h2 class="site-title">Lernen Sie unsere Experten kennen <span>Team</span></h2>
                        <div class="heading-divider"></div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                @foreach($teams as $team)
                    <div class="col-md-6 col-lg-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="team-img">
                                <picture>
                                    <source srcset="{{ $team?->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE) }}" type="image/webp">
                                    <img src="{{ $team?->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE) }}" alt="{{ $team?->name }}" width="720" height="720" loading="lazy">
                                </picture>
                            </div>
                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="#">{{ $team?->name }}</a></h5>
                                    <span>{{ $team?->job }}</span>
                                </div>
                            </div>
                            <div class="team-social">
                                <a href="{{ $team?->config()->get('social_media.facebook') ?? 'https://www.facebook.com/#' }}"><i class="fab fa-facebook-f"></i></a>
                                <a href="{{ $team?->config()->get('social_media.twitter') ?? 'https://x.com/#' }}"><i class="fab fa-x-twitter"></i></a>
                                <a href="{{ $team?->config()->get('social_media.linkedin') ?? 'https://www.linkedin.com/in/#' }}"><i class="fab fa-linkedin-in"></i></a>
                                <a href="{{ $team?->config()->get('social_media.youtube') ?? 'https://www.youtube.com/#' }}"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- team-area end -->


    <!-- partner area -->
    <div class="partner-area pt-50 pb-50">
        <div class="container">
            <div class="partner-wrapper partner-slider owl-carousel owl-theme">
                @foreach($brands as $brand)
                    <picture>
                        <source srcset="{{ $brand?->getFirstMediaUrl('image', 'logo-small') }}" type="image/webp">
                        <img src="{{ $brand?->getFirstMediaUrl('image', 'logo-small') }}" alt="{{ $brand?->title }}" class="brand-logo" loading="lazy">
                    </picture>
                @endforeach
            </div>
        </div>
    </div>
    <!-- partner area end -->
</div>
