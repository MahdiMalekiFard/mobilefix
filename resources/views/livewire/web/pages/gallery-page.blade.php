<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Fotogalerie</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Fotogalerie</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- gallery-area -->
    <div class="gallery-area py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Fotogalerie</span>
                        <h2 class="site-title">Foto erkunden <span>Galerie</span></h2>
                        <div class="heading-divider"></div>
                    </div>
                    <div class="filter-controls wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                        <ul class="filter-btns">
                            <li class="active" data-filter="*"><i class="far fa-computer-speaker"></i> Alle</li>
                            <li data-filter=".cat1"><i class="far fa-mobile"></i> Telefon</li>
                            <li data-filter=".cat2"><i class="far fa-tablet"></i> iPad</li>
                            <li data-filter=".cat3"><i class="far fa-tablet"></i> Tablette</li>
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
                <div class="col-md-4 filter-item cat1">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/m2.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/m2.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 filter-item cat3">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/t2.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/t2.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 filter-item cat1">
                    <div class="gallery-item">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/images/gallery/m4.jpg') }}" alt="">
                        </div>
                        <div class="gallery-content">
                            <a class="popup-img gallery-link" href="{{ asset('assets/images/gallery/m4.jpg') }}"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- gallery-area end -->
</div>
