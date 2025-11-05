
<!DOCTYPE html>
<html>
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>SMK Negeri 1 Belimbing</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap.min.css') }}">
      <!-- style css -->
      <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
      <!-- Responsive-->
      <link rel="stylesheet" href="{{ url('css/responsive.css') }}">
      <!-- fevicon -->
      <link rel="icon" href="{{ url('images/logo.jpg') }}" type="image/gif" />
      <!-- font css -->
      <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="{{ url('css/jquery.mCustomScrollbar.min.css') }}">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   </head>
    <body>
        <div class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <a class="navbar-brand"href="#"><img src="{{ url('images/logo.jpg') }}" alt="Logo" width="60" height="60" ></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="index.html">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.html">Profil Sekolah</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="alumniDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Alumni
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="alumniDropdown">
                                    <li><a class="dropdown-item" href="/alumni/biodata">Biodata</a></li>
                                    <li><a class="dropdown-item" href="/alumni/data">Data Alumni</a></li>
                                    <li><a class="dropdown-item" href="/alumni/riwayat-lamaran">Riwayat Lamaran</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="shop.html">Lowongan Kerja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="services.html">Event</a>
                            </li>
                        </ul>
                        <form class="form-inline my-2 my-lg-0">
                            <div class="login_bt">
                                <ul>
                                    <li><a href="{{ url('login') }}">Login</a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
            <!-- banner section start -->
            <div class="banner_section layout_padding">
                <div class="container">
                    @yield('content')
                </div>
            </div>
            <!-- banner section end -->
        </div>
        <!-- Javascript files-->
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery-3.0.0.min.js"></script>
        <script src="js/plugin.js"></script>
        <!-- sidebar -->
        <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="js/custom.js"></script>
        <!-- kalau sudah ada, cukup pastikan yang dipakai adalah *bundle* -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

        <!-- javascript -->
        <script>
            // Material Select Initialization
            $(document).ready(function() {
            $('.mdb-select').materialSelect();
            $('.select-wrapper.md-form.md-outline input.select-dropdown').bind('focus blur', function () {
            $(this).closest('.select-outline').find('label').toggleClass('active');
            $(this).closest('.select-outline').find('.caret').toggleClass('active');
            });
            });
        </script>
    </body>
</html>
