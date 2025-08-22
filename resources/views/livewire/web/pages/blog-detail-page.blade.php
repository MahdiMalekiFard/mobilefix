@php
    use App\Helpers\Constants;
@endphp
<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Blogdetails</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Blogdetails</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- blog single area -->
    <div class="blog-single-area pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-single-wrap">
                        <div class="blog-single-content">
                            <div class="blog-thumb-img">
                                <img src="{{ $blog->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720) }}"  alt="thumb">
                            </div>
                            <div class="blog-info">
                                <div class="blog-details">
                                    <h3 class="blog-details-title mb-20">{{ $blog->title }}</h3>
                                    <p class="mb-10">
                                        {!! $blog->body !!}
                                    </p>

                                    <hr>
                                    <div class="blog-details-tags pb-20">
                                        <h5>Schlagwörter : </h5>
                                        <ul>
                                            @foreach($blog->tags ?? [] as $tag)
                                                <li><a href="{{ route('blog-page', ['tag' => $tag?->name]) }}">{{ $tag?->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar">
                        <!-- category -->
                        <div class="widget category">
                            <h5 class="widget-title">Kategorien</h5>
                            <div class="category-list">
                                @foreach($categories as $category)
                                    <a href="#"><i class="far fa-angle-double-right"></i>{{ $category?->title }}<span>({{ $category?->blogs->count() }})</span></a>
                                @endforeach
                            </div>
                        </div>
                        <!-- recent post -->
                        <div class="widget recent-post">
                            <h5 class="widget-title">Aktuelle Blogeinträge</h5>
                            @foreach($recentBlogs as $recentBlog)
                                <div class="recent-post-single">
                                    <div class="recent-post-img">
                                        <img src="{{ $recentBlog?->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}" alt="thumb">
                                    </div>
                                    <div class="recent-post-bio">
                                        <h6><a href="{{ route('blog-detail-page', ['slug' => $recentBlog?->slug]) }}">{{ \Illuminate\Support\Str::words($recentBlog?->title, 6) }}</a></h6>
                                        <span><i class="far fa-clock"></i>{{ $recentBlog?->updated_at->format('d M, Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!-- blog single area end -->
</div>
