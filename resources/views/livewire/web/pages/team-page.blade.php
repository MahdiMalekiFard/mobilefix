<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Unser Team</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Unser Team</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- team-area -->
    <div class="team-area bg pt-120 pb-30">
        <div class="container">
            <div class="row mt-5">
                @foreach($teams as $team)
                    <div class="col-md-6 col-lg-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="team-img">
                                <img src="{{ $team?->getFirstMediaUrl('image') }}" alt="thumb">
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

            <!-- pagination -->
            <div class="pagination-area">
                {{ $teams->onEachSide(1)->links() }}
            </div>
            <!-- pagination end-->

        </div>
    </div>
    <!-- team-area end -->
</div>
