<!-- header area -->
<header class="header">

    <!-- header-top -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Follow Us:</span>
                        <a href="https://www.facebook.com/#"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/#"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/#"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            <li>
                                <div class="header-top-contact-info">
                                    <a href="tel:+4976489939"><i class="far fa-phone-volume"></i> +49 7648 9939</a>
                                </div>
                            </li>
                            <li>
                                <div class="header-top-contact-info">
                                    <a href="mailto:info@Fix-mobil.de"><i class="far fa-envelopes"></i>
                                    info@Fix-mobil.de</a>
                                </div>
                            </li>
                            <li>
                                <div class="header-top-contact-info">
                                    <a href="#"><i class="far fa-clock"></i> Sun - Fri (08AM - 10PM)</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header-top end -->

    <!-- navbar -->
    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="logo">
                </a>
                <div class="mobile-menu-right">
                    <div class="mobile-menu-btn">
                        <a href="#" class="nav-right-link search-box-outer"><i class="far fa-search"></i></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                        aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <a href="index.html" class="offcanvas-brand" id="offcanvasNavbarLabel">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="">
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home-page') ? 'active' : '' }}" href="{{ route('home-page') }}">Home</a>
                            </li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('about-us-page') ? 'active' : '' }}" href="{{ route('about-us-page') }}">About</a></li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('service-page') }}">Services</a>
                            </li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('gallery-page') ? 'active' : '' }}" href="gallery.html">Gallery</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog-page') ? 'active' : '' }}" href="{{ route('blog-page') }}">Blog</a></li>    
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact-us-page') ? 'active' : '' }}" href="{{ route('contact-us-page') }}">Contact</a></li>
                        </ul>
                        <!-- nav-right -->
                        <div class="nav-right">
                            <div class="search-btn">
                                <button type="button" class="nav-right-link search-box-outer"><i
                                        class="far fa-search"></i></button>
                            </div>
                            <div class="nav-btn">
                                <a href="{{ route('user.auth.login') }}" class="theme-btn">Login <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- navbar end -->

</header>
<!-- header area end -->