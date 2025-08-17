@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
@endphp

<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url(assets/images/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Our Blog</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Home</a></li>
                <li class="active">Our Blog</li>
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
                        <span class="blog-date"><i class="far fa-calendar-alt"></i> {{ $blog->published_at->format('M d, Y') }}</span>
                        <div class="blog-item-img">
                            <img src="{{ $blog->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}" alt="Thumb">
                        </div>
                        <div class="blog-item-info">
                            <h4 class="blog-title">
                                <a href="{{ route('blog-detail-page', ['slug' => $blog->slug]) }}">{{ Str::limit($blog->title, 50) }}</a>
                            </h4>
                            <div class="blog-item-meta">
                                <ul>
                                    <span>
                                        <li><i class="far fa-user-circle"></i> By {{ $blog->user->name }}</li>
                                    </span>
                                </ul>
                            </div>
                            <p>
                                {{ Str::limit($blog->description, 100) }}
                            </p>
                            <a class="theme-btn" href="{{ route('blog-detail-page', ['slug' => $blog->slug]) }}">Read More<i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- pagination -->
            <div class="pagination-area">
                <div aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- pagination end-->
            
        </div>
    </div>
    <!-- blog-area end -->
</div>
