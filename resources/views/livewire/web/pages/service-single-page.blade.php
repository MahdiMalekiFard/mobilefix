@php
    use App\Helpers\Constants;
@endphp
<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">{{ $service?->title }}</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Service einzeln</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- service-single -->
    <div class="service-single-area py-120">
        <div class="container">
            <div class="service-single-wrapper">
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="service-sidebar">
                            <div class="widget category">
                                <h4 class="widget-title">Alle Leistungen</h4>
                                <div class="category-list">
                                    @foreach($otherServices as $otherService)
                                        <a href="{{ route('service-single-page', ['slug' => $otherService?->slug]) }}"><i class="far fa-angle-double-right"></i>{{ $otherService?->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8">
                        <div class="service-details">
                            <div class="service-details-img mb-30">
                                <img src="{{ $service->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}" alt="thumb">
                            </div>
                            <div class="service-details">
                                <h3 class="mb-30">{{ $service->title }}</h3>
                                <p class="mb-20">
                                    {{ $service->description }}
                                </p>
                                <hr>
                                {!! $service->body !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- service-single end-->
</div>
