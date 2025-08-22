@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
@endphp
<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">FAQ</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">FAQ</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- faq area -->
    <div class="faq-area py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-content wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="mt-3">
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
</div>
