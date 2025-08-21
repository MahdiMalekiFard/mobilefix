@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
@endphp
<div>
    <!-- hero slider -->
    <div class="hero-section">
        <div class="hero-slider owl-carousel owl-theme">
            @foreach($sliders as $slider)
                <div class="hero-single" style="background: url({{ $slider?->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720) }})">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <div class="hero-content">
                                    <h1 class="hero-title" data-animation="fadeInUp" data-delay=".50s">
                                        {{ $slider?->title }}
                                    </h1>
                                    <p data-animation="fadeInUp" data-delay=".75s">
                                        {{ $slider?->description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- hero slider end -->


    <!-- appointment start -->
    @livewire('web.forms.request-repair-form')
    <!-- appointment end -->


    <!-- feature area -->
    <div class="feature-area pt-120">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-item">
                        <span class="count">01</span>
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/icon/repair.svg') }}" alt="">
                        </div>
                        <div class="feature-content">
                            <h4>Bester Elektronik-Reparaturservice</h4>
                            <p>Es ist eine seit langem bekannte Tatsache, dass ein Leser durch den lesbaren Inhalt abgelenkt wird</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-item">
                        <span class="count">02</span>
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/icon/team.svg') }}" alt="">
                        </div>
                        <div class="feature-content">
                            <h4>Reparatur mit erfahrenem Team</h4>
                            <p>Es ist eine seit langem bekannte Tatsache, dass ein Leser durch den lesbaren Inhalt abgelenkt wird</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-item">
                        <span class="count">03</span>
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/icon/secure.svg') }}" alt="">
                        </div>
                        <div class="feature-content">
                            <h4>100% sicherer Reparaturservice</h4>
                            <p>Es ist eine seit langem bekannte Tatsache, dass ein Leser durch den lesbaren Inhalt abgelenkt wird</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- feature area end -->


    <!-- about area -->
    <div class="about-area py-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-left wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="about-img">
                            <div class="about-img-1">
                                <img src="{{ $aboutUsPage?->getFirstMediaUrl('images') }}" alt="">
                            </div>
                            <div class="about-img-2">
                                <img src="{{ $aboutUsPage?->getLastMediaUrl('images') }}" alt="">
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
                                {{ $aboutUsPage?->title }}
                            </h2>
                        </div>
                        <p class="about-text">
                            {{ $aboutUsPage?->body }}
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
                        <a href="{{ route('about-us-page') }}" class="theme-btn mt-4">Mehr entdecken <i
                                class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about area end -->


    <!-- service-area -->
    <div class="service-area sa-bg pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="site-heading">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Leistungen</span>
                        <h2 class="site-title text-white">Schauen wir uns unsere beste <span>Leistungen</span> In der Stadt</h2>
                        <p class="text-white">
                            Es gibt viele Variationen von Passagen mit eingebrachtem Humor, zufällig ausgewählten Wörtern,
                            die nicht einmal annähernd glaubwürdig aussehen (die Mehrheit hat in irgendeiner Form eine Veränderung erfahren),
                            vordefinierten Abschnitten nach Bedarf und eingebrachtem Humor.
                        </p>
                    </div>
                </div>
                @foreach($services as $service)
                    <div class="col-md-6 col-lg-3">
                        <div class="service-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="service-icon">
                                <img src="{{ asset('assets/images/icon/tab.svg') }}" alt="">
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">
                                    <a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}">{{ $service?->title }}</a>
                                </h3>
                                <p class="service-text">
                                    {{ $service?->description }}
                                </p>
                                <div class="service-arrow">
                                    <a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}" class="service-btn"><i class="far fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- service-area -->


    <!-- video area -->
    <div class="video-area py-100">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-4">
                    <div class="site-heading mb-0 wow fadeInLeft" data-wow-delay=".25s">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Latest Video</span>
                        <h2 class="site-title">What makes us <span>different</span> check our video</h2>
                        <p>
                            There are many variations of passages available but the majority have suffered alteration in some form by injected humour randomised words look even going to use a passage believable.
                        </p>
                        <a href="{{ route('about-us-page') }}" class="theme-btn mt-20">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="video-content wow fadeInRight" data-wow-delay=".25s" style="background-image: url({{ asset('assets/images/video/02.jpg') }});">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <div class="video-wrap">
                                    <a class="play-btn popup-youtube" href="https://www.youtube.com/watch?v=ckHzmP1evNU">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- video area end -->


    <!-- choose area -->
    <div class="choose-area py-120">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="choose-content wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Why Choose Us</span>
                            <h2 class="site-title">
                                When You Need Repair <span>We Are</span> Always Here
                            </h2>
                        </div>
                        <p>
                            There are many variations of passages of Lorem Ipsum available but the randomised words which don't
                            look even slightly believable.
                        </p>
                        <div class="choose-wrapper mt-4">
                            <div class="choose-item">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/team-2.svg') }}" alt="">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Skilled Technicians</h4>
                                    <p>It is a long established fact that a reader will distracted the readable content page when looking its layout.</p>
                                </div>
                            </div>
                            <div class="choose-item active">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/quality.svg') }}" alt="">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Quality Guarantee</h4>
                                    <p>It is a long established fact that a reader will distracted the readable content page when looking its layout.</p>
                                </div>
                            </div>
                            <div class="choose-item">
                                <div class="choose-icon">
                                    <img src="{{ asset('assets/images/icon/trusted.svg') }}" alt="">
                                </div>
                                <div class="choose-item-content">
                                    <h4>Your Trusted Partner</h4>
                                    <p>It is a long established fact that a reader will distracted the readable content page when looking its layout.</p>
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


    <!-- cta-area -->
    <div class="cta-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <div class="cta-text">
                        <h1>We Provide <span>Quality</span> Services</h1>
                        <p>
                            It is a long established fact that a reader will be distracted by the readable content of a page when
                            looking at its layout have suffered in some form by injected humour.
                        </p>
                    </div>
                    <div class="mb-20 mt-10">
                        <a href="#" class="cta-border-btn"><i class="fal fa-headset"></i>+49 7648 9939</a>
                    </div>
                    <a href="{{ route('contact-us-page') }}" class="theme-btn">Contact Now <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- cta-area end -->


    <!-- counter area -->
    <div class="counter-area">
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
                                <h6 class="title">+ Projects Done </h6>
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
                                <h6 class="title">+ Happy Clients</h6>
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
                                <h6 class="title">+ Experts Staffs</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="{{ asset('assets/images/icon/award.svg') }}" alt="">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="50" data-speed="3000">50</span>
                                <h6 class="title">+ Win Awards</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter area end -->


    <!-- gallery-area -->
    <div class="gallery-area py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Photo Gallery</span>
                        <h2 class="site-title">Explore Photo <span>Gallery</span></h2>
                        <div class="heading-divider"></div>
                    </div>
                    <div class="filter-controls wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                        <ul class="filter-btns">
                            <li class="active" data-filter="*"><i class="far fa-computer-speaker"></i> All</li>
                            <li data-filter=".cat1"><i class="far fa-mobile"></i> Phone</li>
                            <li data-filter=".cat2"><i class="far fa-tablet"></i> IPad</li>
                            <li data-filter=".cat3"><i class="far fa-tablet"></i> Tablet</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-3 filter-box popup-gallery wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                <div class="col-md-4 filter-item cat2">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/i2.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/i2.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 filter-item cat2">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/i1.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/i1.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 filter-item cat1">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/m1.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/m1.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 filter-item cat1">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/m3.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/m3.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 filter-item cat3">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/t1.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/t1.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- gallery-area end -->


    <!-- team-area -->
    <div class="team-area bg pt-80 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Our Team</span>
                        <h2 class="site-title">Meet Our Experts <span>Team</span></h2>
                        <div class="heading-divider"></div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="team-img">
                            <img src="{{ asset('assets/images/team/01.jpg') }}" alt="thumb">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Chad Smith</a></h5>
                                <span>Technician</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="https://www.facebook.com/#"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/#"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.youtube.com/#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                        <div class="team-img">
                            <img src="{{ asset('assets/images/team/02.jpg') }}" alt="thumb">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Arron Rodri</a></h5>
                                <span>CEO & Founder</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="https://www.facebook.com/#"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/#"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.youtube.com/#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                        <div class="team-img">
                            <img src="{{ asset('assets/images/team/03.jpg') }}" alt="thumb">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Malissa Fie</a></h5>
                                <span>Technician</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="https://www.facebook.com/#"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/#"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.youtube.com/#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">
                        <div class="team-img">
                            <img src="{{ asset('assets/images/team/04.jpg') }}" alt="thumb">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Tony Pinto</a></h5>
                                <span>Technician</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="https://www.facebook.com/#"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/#"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.youtube.com/#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- team-area end -->


    <!-- faq area -->
    <div class="faq-area py-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="faq-left wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="faq-img">
                            <img src="{{ asset('assets/images/faq/01.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-right wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Faq's</span>
                            <h2 class="site-title my-3">General <span>frequently</span> asked questions</h2>
                        </div>
                        <p class="about-text">There are many variations of passages of Lorem Ipsum available,
                            but the majority have suffered alteration in some form by injected.</p>
                        <div class="mt-4">
                            <div class="accordion" id="accordionExample">
                                @foreach($faqs as $index => $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                            <button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}" aria-controls="collapse{{ $index }}">
                                                <span><i class="far fa-question"></i></span> {{ $faq?->title }}{{ Str::contains($faq?->title, '?') ? '' : ' ?' }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}"
                                             aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                {{ $faq?->description }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- faq area end -->


    <!-- testimonial-area -->
    <div class="testimonial-area py-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Testimonials</span>
                        <h2 class="site-title text-white">What Client <span>Say's</span> About Us</h2>
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


    <!-- blog-area -->
    <div class="blog-area py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Our Blogs</span>
                        <h2 class="site-title">Latest <span>Blogs</span></h2>
                        <div class="heading-divider"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <span class="blog-date"><i class="far fa-calendar-alt"></i> {{ $blog?->updated_at->format('M d, Y') }}</span>
                            <div class="blog-item-img">
                                <img src="{{ $blog?->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}" alt="Thumb">
                            </div>
                            <div class="blog-item-info">
                                <h4 class="blog-title">
                                    <a href="{{ route('blog-detail-page', ['slug' => $blog?->slug]) }}">{{ \Illuminate\Support\Str::limit($blog?->title, 50) }}</a>
                                </h4>
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><span href="#"><i class="far fa-user-circle"></i> By {{ $blog?->user?->name }}</span></li>
                                    </ul>
                                </div>
                                <p>
                                    {{ \Illuminate\Support\Str::limit($blog?->description, 100) }}
                                </p>
                                <a class="theme-btn" href="{{ route('blog-detail-page', ['slug' => $blog?->slug]) }}">Read More<i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- blog-area end -->


    <!-- partner area -->
    <div class="partner-area bg pt-50 pb-50">
        <div class="container">
            <div class="partner-wrapper partner-slider owl-carousel owl-theme">
                @foreach($brands as $brand)
                    <img src="{{ $brand?->getFirstMediaUrl('image') }}" alt="thumb">
                @endforeach
            </div>
        </div>
    </div>
    <!-- partner area end -->
</div>
