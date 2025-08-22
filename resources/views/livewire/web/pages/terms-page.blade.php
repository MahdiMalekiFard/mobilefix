<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">{{ $page?->title }}</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">{{ $page?->title }}</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- terms of service -->
    <div class="py-120">
        <div class="container">
            <div class="row">
                <div class="col">
                    {!! $page?->body !!}
                </div>
            </div>
        </div>
    </div>
    <!-- terms of service end -->
</div>
