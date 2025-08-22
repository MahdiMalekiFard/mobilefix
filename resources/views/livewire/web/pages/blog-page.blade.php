@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
@endphp

<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url(assets/images/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Unsere Blogliste</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Blogliste</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- blog-area -->
    <div class="blog-area py-120">
        <div class="container">
            <div class="row">
                @foreach ($blogs as $blog)
                <div class="col-md-6 col-lg-4">
                    <div class="blog-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <span class="blog-date"><i class="far fa-calendar-alt"></i> {{ $blog?->published_at->format('M d, Y') }}</span>
                        <div class="blog-item-img">
                            <img src="{{ $blog?->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}" alt="Thumb">
                        </div>
                        <div class="blog-item-info">
                            <h4 class="blog-title">
                                <a href="{{ route('blog-detail-page', ['slug' => $blog?->slug]) }}">{{ Str::limit($blog?->title, 50) }}</a>
                            </h4>
                            <div class="blog-item-meta">
                                <ul>
                                    <span>
                                        <li><i class="far fa-user-circle"></i> Von {{ $blog?->user->name }}</li>
                                    </span>
                                </ul>
                            </div>
                            <p>
                                {{ Str::limit($blog?->description, 80) }}
                            </p>
                            <a class="theme-btn" href="{{ route('blog-detail-page', ['slug' => $blog?->slug]) }}">Mehr lesen<i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- pagination -->
            <div class="pagination-area">
                {{ $blogs->onEachSide(1)->links() }}
            </div>
            <!-- pagination end-->

        </div>
    </div>
    <!-- blog-area end -->
</div>
