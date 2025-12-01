<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url(assets/images/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Leistungen</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Leistungen</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- service-area -->
    <div class="service-area2 bg py-120">
        <div class="container">
            <div class="row g-5">
                @foreach($services as $service)
                    <div class="col-12 col-sm-6 col-lg-4 mb-4">
                        <div class="service-item h-100 d-flex flex-column wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="service-img">
                                <img src="{{ $service?->getFirstMediaUrl('image', '854x480') }}" alt="{{ $service?->title }}" class="img-fluid w-100">
                            </div>

                            <div class="service-item-wrap flex-grow-1 d-flex flex-column">
                                <div class="service-icon">
                                    <img src="{{ $service?->getIconUrlAttribute() ?? asset('assets/images/icon/tab.svg') }}" alt="">
                                </div>
                                <div class="service-content flex-grow-1 d-flex flex-column">
                                    <h3 class="service-title">
                                        <a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}">{{ $service?->title }}</a>
                                    </h3>
                                    <p class="service-text flex-grow-1">
                                        {{ \Illuminate\Support\Str::limit($service?->description, 100) }}
                                    </p>
                                    <div class="service-arrow mt-auto">
                                        <a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}" class="theme-btn">Mehr Lesen<i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- pagination -->
            <div class="pagination-area">
                {{ $services->onEachSide(1)->links() }}
            </div>
            <!-- pagination end-->
        </div>
    </div>
    <!-- service-area -->
</div>
