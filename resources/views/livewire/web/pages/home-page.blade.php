@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
    
    // Get first slider for preload
    $firstSlider = $sliders->first();
    $firstSliderImage = $firstSlider?->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);
@endphp

@push('head')
    @if($firstSliderImage)
        <link rel="preload" as="image" href="{{ $firstSliderImage }}" fetchpriority="high">
    @endif
    <style>
        /* Prevent CLS - Reserve space for hero slider */
        .hero-section {
            min-height: 600px;
            position: relative;
        }
        .hero-single {
            min-height: 600px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        /* Prevent CLS for images */
        img {
            max-width: 100%;
            height: auto;
        }
        /* Aspect ratio containers for gallery images */
        .gallery-img img,
        .gallery-video img {
            aspect-ratio: 4 / 3;
            object-fit: cover;
        }
        /* Aspect ratio for blog images */
        .blog-item-img img {
            aspect-ratio: 16 / 9;
            object-fit: cover;
        }
        /* Aspect ratio for team images */
        .team-img img {
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }
        /* Aspect ratio for about images */
        .about-img-1 img {
            aspect-ratio: 4 / 5;
            object-fit: cover;
        }
        .about-img-2 img {
            aspect-ratio: 3 / 4;
            object-fit: cover;
        }
        /* Aspect ratio for choose images */
        .choose-img .img-1,
        .choose-img .img-2 {
            aspect-ratio: 4 / 5;
            object-fit: cover;
        }
        /* Prevent CLS for video area */
        .video-content {
            min-height: 400px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
@endpush

<div>
    <!-- hero slider -->
    <div class="hero-section">
        <div class="hero-slider owl-carousel owl-theme">
            @foreach($sliders as $index => $slider)
                @php
                    $sliderImage = $slider?->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);
                @endphp
                <div class="hero-single" style="background: url({{ $sliderImage }})">
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
                            <img src="{{ asset('assets/images/icon/repair.svg') }}" alt="" width="60" height="60" loading="lazy">
                        </div>
                        <div class="feature-content">
                            <h4>Bester Elektronik-Reparaturservice</h4>
                            <p>Schnelle und zuverlässige Reparaturen für Smartphones, Tablets und mehr.
                                Qualität und Präzision stehen bei uns an erster Stelle.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-item">
                        <span class="count">02</span>
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/icon/team.svg') }}" alt="" width="60" height="60" loading="lazy">
                        </div>
                        <div class="feature-content">
                            <h4>Reparatur mit erfahrenem Team</h4>
                            <p>Unser geschultes Fachpersonal kennt jedes Detail moderner Geräte.
                                Jahrelange Erfahrung sorgt für perfekte Ergebnisse.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-item">
                        <span class="count">03</span>
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/icon/secure.svg') }}" alt="" width="60" height="60" loading="lazy">
                        </div>
                        <div class="feature-content">
                            <h4>100% sicherer Reparaturservice</h4>
                            <p>Ihre Daten und Geräte sind bei uns in sicheren Händen.
                                Wir arbeiten transparent, professionell und mit voller Garantie.</p>
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
                                <img src="{{ $aboutUsPage?->getFirstMediaUrl('images') }}" alt="" width="400" height="500" loading="lazy">
                            </div>
                            <div class="about-img-2">
                                <img src="{{ $aboutUsPage?->getLastMediaUrl('images') }}" alt="" width="300" height="400" loading="lazy">
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
                        <h2 class="site-title text-white">Schauen wir uns unsere besten <span>Dienstleistungen</span> In der Stadt an</h2>
                        <p class="text-white">
                            Wir bieten Ihnen eine Auswahl unserer beliebtesten und zuverlässigsten Reparaturservices direkt vor Ort.
                            Ob Displaywechsel, Akku-Austausch oder komplexere Elektronikreparaturen – entdecken Sie die Leistungen,
                            die uns in der Stadt zu einer der ersten Adressen für schnelle und professionelle Hilfe machen.
                        </p>
                    </div>
                </div>
                @foreach($services as $service)
                    <div class="col-md-6 col-lg-3">
                        <div class="service-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="service-icon">
                                <img src="{{ $service?->getIconUrlAttribute() ?? asset('assets/images/icon/tab.svg') }}" alt="" width="60" height="60" loading="lazy">
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
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Aktuelles Video</span>
                        <h2 class="site-title">Was uns ausmacht <span>anders</span> Schauen Sie sich unser Video an</h2>
                        <p>
                            Bei uns geht es nicht nur um Reparaturen, sondern um Qualität, Vertrauen und einen Service, auf den Sie sich verlassen können.
                            In unserem Video zeigen wir Ihnen, wie unsere erfahrenen Techniker arbeiten,
                            welche Sorgfalt wir in jede Reparatur stecken und warum so viele Kunden in Deutschland uns als ihren zuverlässigen Partner wählen.
                        </p>
                        <a href="{{ route('about-us-page') }}" class="theme-btn mt-20">Mehr erfahren <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="video-content wow fadeInRight" data-wow-delay=".25s" style="background-image: url({{ asset('assets/images/video/02.jpg') }});">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <div class="video-wrap">
                                    <a class="play-btn popup-youtube" href="{{ asset('assets/videos/home.mp4') }}">
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


    <!-- cta-area -->
    <div class="cta-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <div class="cta-text">
                        <h1>Wir bieten <span>Qualität</span> Leistungen</h1>
                        <p>
                            Wir bieten Qualitätsleistungen – zuverlässig, präzise und nachhaltig.
                        </p>
                    </div>
                    <div class="mb-20 mt-10">
                        <a href="#" class="cta-border-btn"><i class="fal fa-headset"></i>+49 7648 9939</a>
                    </div>
                    <a href="{{ route('contact-us-page') }}" class="theme-btn">Jetzt kontaktieren <i class="fas fa-arrow-right"></i></a>
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
                                <span class="counter" data-count="+" data-to="80" data-speed="3000" data-suffix="%">80%</span>
                                <h6 class="title">+ Zufriedenheit</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter area end -->

    @if($artGalleries->isNotEmpty())
        <!-- gallery-area -->
        <div class="gallery-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="site-heading text-center wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Galerien</span>
                            <h2 class="site-title">Entdecken Sie unsere <span>Galerie</span></h2>
                            <div class="heading-divider"></div>
                        </div>

                        {{-- FILTER BUTTONS --}}
                        <div class="filter-controls wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                            <ul class="filter-btns">
                                <li class="active" data-filter="*">
                                    <i class="fa-solid fa-layer-group"></i> Alle
                                </li>

                                @foreach($artGalleries as $gallery)
                                    @php
                                        $slug       = $gallery->slug ?? Str::slug($gallery->title);
                                        $iconKey    = $gallery->icon; // e.g. 'phone'
                                        $iconClass  = config('font_awesome.icons')[$iconKey] ?? 'fa-regular fa-image';
                                    @endphp

                                    <li data-filter=".cat-{{ $slug }}">
                                        <i class="{{ $iconClass }}"></i> {{ $gallery->title }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- FILTERABLE GRID --}}
                <div class="row mt-3 filter-box popup-gallery wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                    @foreach($artGalleries as $gallery)
                        @php
                            $slug = $gallery->slug ?? Str::slug($gallery->title);

                            // Load media
                            $images = $gallery->getMedia('images');
                            $videos = $gallery->getMedia('videos');

                            // EXCLUDE video posters that were stored in "images"
                            $posterIds = $videos->map(fn($v) => $v->getCustomProperty('poster_media_id'))
                                  ->filter()
                                  ->values()
                                  ->all();
                            if (!empty($posterIds)) {
                                $images = $images->reject(fn($m) => in_array($m->id, $posterIds, true));
                            }
                        @endphp

                        {{-- Images --}}
                        @foreach($images as $media)
                            <div class="col-md-4 filter-item cat-{{ $slug }}">
                                <div class="gallery-item">
                                    <div class="gallery-img">
                                        <img
                                            src="{{ $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(Constants::RESOLUTION_1280_720) }}"
                                            alt="{{ $gallery->title }}"
                                            width="400"
                                            height="300"
                                            loading="lazy">
                                    </div>
                                    <div class="gallery-content">
                                        <a class="popup-img gallery-link" href="{{ $media->getUrl() }}">
                                            <i class="far fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Videos (use poster) --}}
                        @foreach($videos as $video)
                            @php
                                if ($posterId = $video->getCustomProperty('poster_media_id')) {
                                    $posterUrl = Media::find($posterId)?->getUrl(Constants::RESOLUTION_1280_720) ?? '#';
                                } else {
                                    $posterUrl = asset('assets/images/default/video_poster.jpg');
                                }
                            @endphp

                            <div class="col-md-4 filter-item cat-{{ $slug }}">
                                <div class="gallery-video">
                                    <a href="{{ $video->getUrl() }}"
                                       target="_blank"
                                       class="popup-video"
                                       data-type="video"
                                       data-title="{{ $gallery->title }}"
                                    >
                                        <img src="{{ $posterUrl }}" alt="{{ $gallery->title }} video" width="400" height="300" loading="lazy">

                                        <div class="video-icon">
                                            <i class="fa-solid fa-play"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    @endforeach

                    {{-- Optional: empty state --}}
                    @php
                        // recompute emptiness after filtering posters out of images
                        $hasMedia = $artGalleries->some(function($g) {
                          $videos = $g->getMedia('videos');
                          $images = $g->getMedia('images');
                          $posterIds = $videos->map(fn($v) => $v->getCustomProperty('poster_media_id'))
                                              ->filter()->values()->all();
                          if (!empty($posterIds)) {
                            $images = $images->reject(fn($m) => in_array($m->id, $posterIds, true));
                          }
                          return $images->isNotEmpty() || $videos->isNotEmpty();
                        });
                    @endphp

                    @unless($hasMedia)
                        <div class="col-12 text-center text-muted py-5">Keine Medien gefunden.</div>
                    @endunless
                </div>
            </div>
        </div>
        <!-- gallery-area end -->
    @endif


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
                                <img src="{{ $team?->getFirstMediaUrl('image') }}" alt="thumb" width="300" height="300" loading="lazy">
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


    <!-- faq area -->
    <div class="faq-area py-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="faq-left wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="faq-img">
                            <img src="{{ asset('assets/images/faq/01.jpg') }}" alt="" width="600" height="600" loading="lazy">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-right wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> FAQ</span>
                            <h2 class="site-title my-3">Allgemein <span>häufig</span> gestellte Fragen</h2>
                        </div>
                        <p class="about-text">
                            Antworten auf die häufigsten Fragen zu unseren Reparaturservices.
                        </p>
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
                                <img src="{{ $opinion?->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}" alt="" width="100" height="100" loading="lazy">
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


    <!-- blog-area -->
    <div class="blog-area py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Bleiben Sie informiert</span>
                        <h2 class="site-title">Letzte <span>Blogs</span></h2>
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
                                <img src="{{ $blog?->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}" alt="Thumb" width="854" height="480" loading="lazy">
                            </div>
                            <div class="blog-item-info">
                                <h4 class="blog-title">
                                    <a href="{{ route('blog-detail-page', ['slug' => $blog?->slug]) }}">{{ \Illuminate\Support\Str::limit($blog?->title, 50) }}</a>
                                </h4>
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><span href="#"><i class="far fa-user-circle"></i> Von {{ $blog?->user?->name }}</span></li>
                                    </ul>
                                </div>
                                <p>
                                    {{ \Illuminate\Support\Str::limit($blog?->description, 100) }}
                                </p>
                                <a class="theme-btn" href="{{ route('blog-detail-page', ['slug' => $blog?->slug]) }}">Mehr lesen<i class="fas fa-arrow-right"></i></a>
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
                    <img src="{{ $brand?->getFirstMediaUrl('image') }}" alt="thumb" width="150" height="80" loading="lazy">
                @endforeach
            </div>
        </div>
    </div>
    <!-- partner area end -->
</div>
