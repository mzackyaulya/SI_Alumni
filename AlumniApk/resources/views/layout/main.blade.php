
<!DOCTYPE html>
<html class="no-js" lang="">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>@yield('title')</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- Place favicon.ico in the root directory -->

        <!-- ========================= CSS here ========================= -->
        <link rel="stylesheet" href="{{ url('assets/css/bootstrap-5.0.0-beta1.min.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/css/LineIcons.2.0.css') }}"/>
        <link rel="stylesheet" href="{{ url('assets/css/tiny-slider.css') }}"/>
        <link rel="stylesheet" href="{{ url('assets/css/animate.css') }}"/>
        <link rel="stylesheet" href="{{ url('assets/css/lindy-uikit.css') }}"/>
    </head>
    <body>
        <!--[if lte IE 9]>
        <p class="browserupgrade">
            You are using an <strong>outdated</strong> browser. Please
            <a href="https://browsehappy.com/">upgrade your browser</a> to improve
            your experience and security.
        </p>
        <![endif]-->

        <!-- ========================= preloader start ========================= -->
        <div class="preloader">
            <div class="loader">
                <div class="spinner">
                    <div class="spinner-container">
                        <div class="spinner-rotator">
                            <div class="spinner-left">
                                <div class="spinner-circle"></div>
                            </div>
                            <div class="spinner-right">
                                <div class="spinner-circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========================= preloader end ========================= -->

        <!-- ========================= hero-section-wrapper-5 start ========================= -->
        <section id="home" class="hero-section-wrapper-5">
            <!-- ========================= header-6 start ========================= -->
            <header class="header header-6">
                <div class="navbar-area">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <nav class="navbar navbar-expand-lg">
                                    <a class="navbar-brand" href="#">
                                        <img src="{{ url('assets/img/logo.jpg') }}" alt="Logo" width="60" height="60" />
                                    </a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent6" aria-controls="navbarSupportedContent6" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="toggler-icon"></span>
                                        <span class="toggler-icon"></span>
                                        <span class="toggler-icon"></span>
                                    </button>

                                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent6">
                                        <ul id="nav6" class="navbar-nav ms-auto">
                                            <li class="nav-item">
                                                <a class="page-scroll active" href="{{ url('dashboard') }}">Beranda</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="page-scroll" href="#">Profil Sekolah</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="page-scroll" href="#">Lowongan Kerja</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="page-scroll" href="{{ url('alumni') }}">Alumni</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="page-scroll" href="#">Event</a>
                                            </li>
                                            {{-- Jika user belum login --}}
                                            @guest
                                                <li class="nav-item">
                                                    <a class="page-scroll" href="{{ url('login') }}">LOGIN</a>
                                                </li>
                                            @endguest

                                            {{-- Jika user sudah login --}}
                                            @auth
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        {{ Auth::user()->name }}
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Logout</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endauth
                                        </ul>
                                    </div>
                                    <!-- navbar collapse -->
                                </nav>
                                <!-- navbar -->
                            </div>
                        </div>
                        <!-- row -->
                    </div>
                    <!-- container -->
                </div>
                <!-- navbar area -->
            </header>
            <!-- ========================= header-6 end ========================= -->

            <!-- ========================= hero-5 start ========================= -->
            <div class="hero-section hero-style-5 img-bg hero-fill" style="background-image: url('assets/img/hero/hero-5/hero-bg.svg')">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </section>
        <!-- ========================= hero-section-wrapper-6 end ========================= -->


        <!-- ========================= JS here ========================= -->
        <script src="{{ url('assets/js/bootstrap-5.0.0-beta1.min.js') }}"></script>
        <script src="{{ url('assets/js/tiny-slider.js') }}"></script>
        <script src="{{ url('assets/js/wow.min.js') }}"></script>
        <script src="{{ url('assets/js/main.js') }}"></script>
    </body>
</html>
