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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
        <style>
            body {
            background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%) !important;
            min-height: 100vh;
            }
            /* atur jarak antara navbar (header) dan konten utama */
            .hero-section.hero-style-5.img-bg.hero-fill {
            min-height: auto !important;
            padding-top: 150px !important;  /* jarak vertikal dari navbar ke tabel */
            padding-bottom: 40px !important; /* biar bawah tetap lega */
            display: block !important;
            align-items: flex-start !important;
            justify-content: flex-start !important;
            background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%) !important;
            }

            /* rapikan kontainer isi */
            .hero-section .container {
            margin-top: 0 !important;
            padding-top: 0 !important;
            }

            /* opsional: beri sedikit shadow lembut agar konten lebih menonjol */
            .card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            }

            /* Reset efek "pil" dari navbar utk item di dalam dropdown */
            .header.header-6 .dropdown-menu .dropdown-item {
            padding: 0.35rem 0.9rem !important;
            border: none !important;
            border-radius: 6px !important;   /* kecil saja */
            margin: 0 !important;
            box-shadow: none !important;
            outline: 0 !important;
            }

            /* Hover / focus / active yang halus */
            .header.header-6 .dropdown-menu .dropdown-item:hover,
            .header.header-6 .dropdown-menu .dropdown-item:focus,
            .header.header-6 .dropdown-menu .dropdown-item.active {
            background: rgba(13,110,253,.10) !important;
            color: #0d6efd !important;
            border: none !important;
            box-shadow: none !important;
            }

            /* (opsional) rapikan kontainer dropdown */
            .header.header-6 .dropdown-menu {
            padding: .5rem !important;
            min-width: 150px; /* atau sesuai kebutuhan */
            }

            /* Sesuaikan tampilan hover & active dropdown alumni */
            .dropdown-menu-alumni .dropdown-item {
            padding: 0.35rem 0.9rem;
            border-radius: 4px;
            }

            .dropdown-menu-alumni .dropdown-item:hover,
            .dropdown-menu-alumni .dropdown-item:focus {
            background-color: rgba(0, 123, 255, 0.08); /* lembut */
            color: #0d6efd;
            }

            /* Hilangkan efek "tombol besar" saat active */
            .dropdown-menu-alumni .dropdown-item.active {
            background-color: rgba(13, 110, 253, 0.12); /* tipis */
            border-radius: 4px;
            font-weight: 600;
            box-shadow: none;
            }
        </style>

    </head>
    <body>
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
            <header class="header header-6 bg-warning">
                <div class="navbar-area">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <nav class="navbar navbar-expand-lg">
                                    <a class="navbar-brand" href="#">
                                        <img src="{{ url('assets/img/logo.jpg') }}" alt="Logo" width="50" height="50" />
                                    </a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent6" aria-controls="navbarSupportedContent6" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="toggler-icon"></span>
                                        <span class="toggler-icon"></span>
                                        <span class="toggler-icon"></span>
                                    </button>

                                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent6">
                                        <ul id="nav6" class="navbar-nav ms-auto">
                                            <li class="nav-item">
                                                <a class="page-scroll {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">Beranda</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="page-scroll" href="#">Profile Sekolah</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="page-scroll {{ request()->routeIs('lowongan') ? 'active' : '' }}" href="{{ url('lowongan') }}">Lowongan Kerja</a>
                                            </li>
                                            {{--Start Menu Alumni --}}
                                            @guest
                                                {{-- Jika belum login, langsung arahkan ke biodata --}}
                                                <li class="nav-item">
                                                    <a class="nav-link {{ request()->routeIs('alumni.biodata') ? 'active' : '' }}"
                                                    href="{{ route('alumni.biodata') }}">
                                                        Alumni
                                                    </a>
                                                </li>
                                            @endguest

                                            @auth
                                                {{-- Jika sudah login, tampilkan dropdown --}}
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle
                                                        {{ request()->routeIs('alumni.index','alumni.create','alumni.edit','alumni.show')
                                                            || request()->routeIs('alumni.biodata') ? 'active' : '' }}"
                                                        href="#" id="navAlumniDropdown" role="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Alumni
                                                    </a>

                                                    <ul class="dropdown-menu dropdown-menu-alumni" aria-labelledby="navAlumniDropdown" style="min-width: 300px;">
                                                        @php
                                                            $user = Auth::user();
                                                            $alumniId = optional($user->alumni)->id
                                                                ?? \App\Models\Alumni::where('user_id', $user->id)->value('id');

                                                            $dataAlumniUrl = ($user && $user->role === 'admin')
                                                                ? route('alumni.index')
                                                                : ($alumniId ? route('alumni.show', $alumniId) : route('alumni.biodata'));
                                                        @endphp
                                                        <li>
                                                            <a class="dropdown-item
                                                                {{ request()->routeIs('alumni.index','alumni.create','alumni.edit','alumni.show') ? 'active' : '' }}"
                                                                href="{{ $dataAlumniUrl }}">
                                                                <i class="fa-solid fa-users me-2"></i> Data Alumni
                                                            </a>
                                                        </li>

                                                        <li class="mt-1">
                                                            <a class="dropdown-item {{ request()->routeIs('alumni.biodata') ? 'active' : '' }}"
                                                                href="{{ route('alumni.biodata') }}">
                                                                <i class="fa-solid fa-id-card me-2"></i> Biodata Alumni
                                                            </a>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                        <li>
                                                            <a class="dropdown-item {{ request()->routeIs('lamaran.*') ? 'active' : '' }}" href="{{ url('lamaran') }}">
                                                                <i class="fa-solid fa-briefcase me-2"></i> Riwayat Lamaran
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endauth
                                            {{--  end Menu Alumni  --}}

                                            {{-- Start Menu Perusahaan --}}
                                            @auth
                                                @php $role = Auth::user()->role ?? null; @endphp

                                                @if(in_array($role, ['admin','company']))
                                                    {{-- Admin & Company: dropdown --}}
                                                    <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle
                                                        {{ request()->routeIs('perusahaan.index','perusahaan.create','perusahaan.edit','perusahaan.show')
                                                            || request()->routeIs('perusahaan.biodata.index','perusahaan.biodata.show')
                                                            ? 'active' : '' }}"
                                                        href="#" id="navPerusahaanDropdown" role="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Perusahaan
                                                    </a>

                                                    <ul class="dropdown-menu dropdown-menu-alumni" aria-labelledby="navPerusahaanDropdown" style="min-width: 300px;">
                                                        <li>
                                                        <a class="dropdown-item
                                                            {{ request()->routeIs('perusahaan.index','perusahaan.create','perusahaan.edit','perusahaan.show') ? 'active' : '' }}"
                                                            href="{{ route('perusahaan.index') }}">
                                                            <i class="fa-solid fa-users me-2"></i> Data Perusahaan
                                                        </a>
                                                        </li>
                                                        <li class="mt-1">
                                                        <a class="dropdown-item
                                                            {{ request()->routeIs('perusahaan.biodata.index','perusahaan.biodata.show') ? 'active' : '' }}"
                                                            href="{{ route('perusahaan.biodata.index') }}">
                                                            <i class="fa-solid fa-id-card me-2"></i> Biodata Perusahaan
                                                        </a>
                                                        </li>
                                                    </ul>
                                                    </li>
                                                @else
                                                    {{-- Role lain (alumni, siswa, guru): hanya 1 link ke biodata --}}
                                                    <li class="nav-item">
                                                    <a class="nav-link {{ request()->routeIs('perusahaan.biodata.index','perusahaan.biodata.show') ? 'active' : '' }}"
                                                        href="{{ route('perusahaan.biodata.index') }}">
                                                        Perusahaan
                                                    </a>
                                                    </li>
                                                @endif
                                            @endauth
                                            {{-- End Menu Perusahaan --}}

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
                                                            <a class="dropdown-item text-center" href="#"><i class="fa-solid fa-user me-2"></i>Profil</a>
                                                        </li>

                                                        <li>
                                                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger text-center"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endauth
                                            {{--  Fitur Register dan saat login akan hilang  --}}
                                            @guest
                                                @if (Route::has('register'))
                                                    <li class="nav-item">
                                                        <a class="page-scroll" href="{{ route('register') }}">REGISTER</a>
                                                    </li>
                                                @endif
                                            @endguest
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
            <div class="hero-section hero-style-5 img-bg hero-fill" style="min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%);
                ">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </section>
        <!-- ========================= hero-section-wrapper-6 end ========================= -->


        <!-- ========================= JS here ========================= -->
        {{--  <script src="{{ url('assets/js/bootstrap-5.0.0-beta1.min.js') }}"></script>  --}}
        <script src="{{ url('assets/js/tiny-slider.js') }}"></script>
        <script src="{{ url('assets/js/wow.min.js') }}"></script>
        <script src="{{ url('assets/js/main.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    </body>
</html>
