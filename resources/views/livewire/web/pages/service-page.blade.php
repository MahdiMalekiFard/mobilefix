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
            <div class="row">
                @foreach($services as $service)
                    <div class="col-md-6 col-lg-4">
                        <div class="service-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="service-img">
                                <img src="{{ $service?->getFirstMediaUrl('image') }}" alt="">
                            </div>

                            <div class="service-item-wrap">
                                <div class="service-icon">
                                    <img src="{{ $service?->getIconUrlAttribute() ?? asset('assets/images/icon/tab.svg') }}" alt="">
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">
                                        <a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}">{{ $service?->title }}</a>
                                    </h3>
                                    <p class="service-text">
                                        {{ \Illuminate\Support\Str::limit($service?->description, 80) }}
                                    </p>
                                    <div class="service-arrow">
                                        <a href="{{ route('service-single-page', ['slug' => $service?->slug]) }}" class="theme-btn"> Mehr lesen<i class="fas fa-arrow-right"></i></a>
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
