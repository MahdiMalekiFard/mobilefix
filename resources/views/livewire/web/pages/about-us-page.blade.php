@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
@endphp
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
                                <img src="{{ $page?->getFirstMediaUrl('images') }}" alt="">
                            </div>
                            <div class="about-img-2">
                                <img src="{{ $page?->getLastMediaUrl('images') }}" alt="">
                            </div>
                        </div>
                        <div class="about-shape"><img src="{{ asset('assets/images/shape/01.png') }}" alt=""></div>
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
                                        <img src="{{ asset('assets/images/icon/money.svg') }}" alt="">
                                    </div>
                                    <div class="content">
                                        <h4>Unser günstiger Preis</h4>
                                        <p>Es gibt viele Variationen dieser Passage, die meisten davon sind in irgendeiner Form mit Humor überladen.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/icon/trusted.svg') }}" alt="">
                                    </div>
                                    <div class="content">
                                        <h4>Vertrauenswürdiger Reparaturservice</h4>
                                        <p>Es gibt viele Variationen dieser Passage, die meisten davon sind in irgendeiner Form mit Humor überladen.</p>
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
                                <img src="{{ asset('assets/images/icon/repair-2.svg') }}" alt="">
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
                                <img src="{{ asset('assets/images/icon/happy.svg') }}" alt="">
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
                                <img src="{{ asset('assets/images/icon/team-2.svg') }}" alt="">
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
                                <img src="{{ asset('assets/images/icon/award.svg') }}" alt="">
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
                            Es sind viele Variationen von Lorem-Ipsum-Abschnitten verfügbar, aber die
                            zufällig ausgewählten Wörter sehen nicht einmal annähernd glaubwürdig aus.
                        </p>
                        <div class="choose-wrapper mt-4">
                            <div class="choose-item">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/team-2.svg') }}" alt="">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Qualifizierte Techniker</h4>
                                    <p>Es ist eine seit langem bekannte Tatsache, dass ein Leser durch den lesbaren Inhalt der Seite abgelenkt wird, wenn er sich das Layout ansieht.</p>
                                </div>
                            </div>
                            <div class="choose-item active">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/quality.svg') }}" alt="">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Qualitätsgarantie</h4>
                                    <p>Es ist eine seit langem bekannte Tatsache, dass ein Leser durch den lesbaren Inhalt der Seite abgelenkt wird, wenn er sich das Layout ansieht.</p>
                                </div>
                            </div>
                            <div class="choose-item">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/trusted.svg') }}" alt="">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Ihr zuverlässiger Partner</h4>
                                    <p>Es ist eine seit langem bekannte Tatsache, dass ein Leser durch den lesbaren Inhalt der Seite abgelenkt wird, wenn er sich das Layout ansieht.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="choose-img wow fadeInRight" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="row g-4">
                            <div class="col-6">
                                <img class="img-1" src="{{ asset('assets/images/choose/01.jpg') }}" alt="">
                            </div>
                            <div class="col-6">
                                <img class="img-2" src="{{ asset('assets/images/choose/02.jpg') }}" alt="">
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
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Referenzen</span>
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
                                <img src="{{ $opinion?->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}" alt="">
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
                                <img src="{{ asset('assets/images/icon/quote.svg') }}" alt="">
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
                                <img src="{{ $team?->getFirstMediaUrl('image') }}" alt="thumb">
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
                <img src="{{ asset('assets/images/partner/samsung.png') }}" alt="thumb">
                <img src="{{ asset('assets/images/partner/nokia.png') }}" alt="thumb">
                <img src="{{ asset('assets/images/partner/huawei.png') }}" alt="thumb">
                <img src="{{ asset('assets/images/partner/xiaomi.png') }}" alt="thumb">
                <img src="{{ asset('assets/images/partner/iphone.png') }}" alt="thumb">
                <img src="{{ asset('assets/images/partner/oppo.png') }}" alt="thumb">
                <img src="{{ asset('assets/images/partner/vivo.png') }}" alt="thumb">
            </div>
        </div>
    </div>
    <!-- partner area end -->
</div>
