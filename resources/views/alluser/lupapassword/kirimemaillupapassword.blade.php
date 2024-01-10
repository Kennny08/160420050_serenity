<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="robots" content="index, follow" />
    <title id="titletitle">
        Serenity || Lupa Password
    </title>
    <meta name="description" content="Jesco - Fashoin eCommerce HTML Template" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Add site Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets_admin/images/logo-serenity/serenity-logo-no-background.png') }}">


    <!-- vendor css (Icon Font) -->
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/vendor/bootstrap.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/vendor/pe-icon-7-stroke.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/vendor/font.awesome.css') }}" type="text/css" />

    <!-- plugins css (All Plugins Files) -->
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/plugins/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/plugins/swiper-bundle.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/plugins/jquery-ui.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/plugins/nice-select.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/plugins/venobox.css') }}" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('assets_pelanggan/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets_pelanggan/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets_pelanggan/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Use the minified version files listed below for better performance and remove the files listed above -->
    {{-- <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/vendor/vendor.min.css') }}"  type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/plugins/plugins.min.css') }}"  type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets_pelanggan/css/style.min.css') }}" type="text/css"> --}}

    <!-- Main Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets_pelanggan/css/style.css') }}" />

</head>

<body>

    <!-- Top Bar -->

    {{-- <div class="header-to-bar"> HELLO EVERYONE! 25% Off All Products </div> --}}

    <!-- Top Bar -->
    <!-- Header Area Start -->
    <header>
        <div class="header-main sticky-nav ">
            <div class="container position-relative">
                <div class="row">
                    <div class="col-auto align-self-center">
                        <div class="header-logo">
                            <a href="{{ route('users.halamanutama') }}"><img
                                    src="{{ asset('assets_admin/images/logo-serenity/serenity-logo-no-background.png') }}"
                                    height="65" alt="Site Logo" /><img class="ml-5px"
                                    src="{{ asset('assets_admin/images/logo-serenity/name_serenity.jpeg') }}"
                                    height="40" alt="Site Logo" /></a>
                        </div>
                    </div>
                    <div class="col align-self-center d-none d-lg-block">
                        <div class="main-menu">
                            <ul>
                                @if (Auth::check())
                                    @if (Auth::user()->role == 'pelanggan')
                                        <li><a href="{{ route('pelanggans.index') }}">Beranda</a>
                                        </li>
                                    @else
                                        <li><a href="{{ route('users.halamanutama') }}">Beranda</a>
                                        </li>
                                    @endif
                                @else
                                    <li><a href="{{ route('users.halamanutama') }}">Beranda</a>
                                    </li>
                                @endif

                                <li class="dropdown "><a href="#">Informasi Salon <i
                                            class="pe-7s-angle-down"></i></a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ route('perawatans.daftarperawatanalluser') }}">Perawatan</a>
                                        </li>
                                        <li><a href="{{ route('produks.daftarprodukalluser') }}">Produk</a></li>
                                        <li><a href=" {{ route('pakets.daftarpaketalluser') }}">Paket</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('users.tentangkami') }}">Tentang Kami</a></li>
                                @if (Auth::check())
                                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'karyawan')
                                        @if (Auth::user()->role == 'admin')
                                            <li><a href="{{ route('allindex') }}">Halaman Admin</a>
                                            </li>
                                        @else
                                            <li><a href="{{ route('allindex') }}">Halaman Karyawan</a>
                                            </li>
                                        @endif
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- Header Action Start -->
                    <div class="col col-lg-auto align-self-center pl-0">
                        <div class="header-actions">

                            @if (Auth::check())
                                <a class="header-action-btn login-btn" href="#"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                        class="fa fa-sign-out" aria-hidden="true"></i> &nbsp; Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route("login") }}" class="header-action-btn login-btn h5" >Log In</a>
                            @endif
                            </a>
                            <a href="#offcanvas-mobile-menu"
                                class="header-action-btn header-action-btn-menu offcanvas-toggle d-lg-none">
                                <i class="pe-7s-menu"></i>
                            </a>
                        </div>
                        <!-- Header Action End -->
                    </div>
                </div>
            </div>
    </header>
    <!-- Header Area End -->
    <div class="offcanvas-overlay"></div>

    <!-- OffCanvas Menu Start -->
    <div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
        <button class="offcanvas-close"></button>

        <div class="inner customScroll">

            <div class="offcanvas-menu mb-4">
                <ul>
                    @if (Auth::check())
                        @if (Auth::user()->role == 'pelanggan')
                            <li><a href="{{ route('pelanggans.index') }}">Beranda</a>
                            </li>
                        @else
                            <li><a href="{{ route('users.halamanutama') }}"><span
                                        class="menu-text">Beranda</span></a>
                            </li>
                        @endif
                    @else
                        <li><a href="{{ route('users.halamanutama') }}"><span class="menu-text">Beranda</span></a>
                        </li>
                    @endif

                    <li><a href="#"><span class="menu-text">Informasi Salon</span></a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('perawatans.daftarperawatanalluser') }}">Perawatan</a></li>
                            <li><a href="{{ route('produks.daftarprodukalluser') }}">Produk</a></li>
                            <li><a href=" {{ route('pakets.daftarpaketalluser') }}">Paket</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('users.tentangkami') }}">Tentang Kami</a></li>
                    @if (Auth::check())
                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'karyawan')
                            @if (Auth::user()->role == 'admin')
                                <li><a href="{{ route('allindex') }}">Halaman Admin</a></li>
                            @else
                                <li><a href="{{ route('allindex') }}">Halaman Karyawan</a></li>
                            @endif
                        @endif
                    @endif
                </ul>
            </div>
            <!-- OffCanvas Menu End -->
            <div class="offcanvas-social mt-auto">
                <ul>
                    <li>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-google"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- OffCanvas Menu End -->

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="login-register-area pt-100px pb-100px">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                                <div class="login-register-wrapper">
                                    <div class="login-register-tab-list nav">
                                        <a class="active" href="{{ route('lupapassword') }}">
                                            <h4>Lupa Password</h4>
                                        </a>
                                    </div>

                                    @if ($errors->any())
                                        <input type="hidden" value="errorLogin" class="errorLogin">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close text-danger" data-bs-dismiss="alert"
                                                aria-label="Close"
                                                style="float: right; position: absolute;top: 0;right: 0;padding: 0.75rem 1.25rem">
                                                <span class="text-danger" aria-hidden="true">&times;</span>
                                            </button>
                                            <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                                            @foreach ($errors->all() as $error)
                                                <p class="mt-0 mb-1">- {{ $error }}</p>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible" role="alert">
                                            <button
                                                style="float: right; position: absolute;top: 0;right: 0;padding: 0.75rem 1.25rem"
                                                type="button" class="close text-danger" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <span class="text-danger" aria-hidden="true">&times;</span>
                                            </button>
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <div class="tab-content">

                                        <div id="lg1" class="tab-pane active">

                                            <div class="login-form-container">

                                                <div class="login-register-form">
                                                    <form action="{{ route('kirimemaillupapassword') }}"
                                                        method="POST">

                                                        @csrf
                                                        <label class="fw-bold h6"
                                                            style="margin-bottom: 20px;">Username</label>
                                                        <input type="text" name="username"
                                                            style="border-radius: 5px;font-size: 1.1em;"
                                                            placeholder="Masukkan Username" required />
                                                        <div style="margin-bottom: 20px">
                                                            <h6 class="text-danger ">
                                                                * Kode OTP penggantian password akan dikirimkan pada
                                                                email
                                                            </h6>
                                                        </div>

                                                        <div class="button-box">

                                                            <button style="float: right; border-radius: 5px;"
                                                                type="submit"><span>Kirim</span></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Area Start -->
    <div class="footer-area m-lrb-30px">
        <div class="footer-container">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <!-- Start single blog -->
                        <div class="col-md-4 col-lg-4 mb-md-30px mb-lm-30px">
                            <div class="single-wedge">
                                <div class="footer-logo">
                                    <a href="{{ route('users.halamanutama') }}">
                                        {{-- <img src="{{ asset('assets_pelanggan/images/logo-serenity/serenity.png') }}"
                                            height="80" alt="Site Logo" /> --}}
                                        <img src="{{ asset('assets_admin/images/logo-serenity/name_serenity.jpeg') }}"
                                            height="50" alt="Site Logo" />
                                </div>
                                <p class="about-text">Where Serenity Meets Magic in Every Style
                                </p>
                                <ul class="link-follow">
                                    <li>
                                        <a class="m-0" title="Twitter" href="#"><i class="fa fa-twitter"
                                                aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a title="Tumblr" href="#"><i class="fa fa-tumblr"
                                                aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a title="Facebook" href="#"><i class="fa fa-facebook"
                                                aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a title="Instagram" href="#"><i class="fa fa-instagram"
                                                aria-hidden="true"></i>
                                            </i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- End single blog -->
                        <!-- Start single blog -->

                        <!-- End single blog -->
                        <!-- Start single blog -->
                        <div class="col-md-4 col-lg-4 col-sm-6 mb-lm-30px pl-lg-50px">
                            <div class="single-wedge">
                                <h4 class="footer-herading">Item Salon</h4>
                                <div class="footer-links">
                                    <div class="footer-row">
                                        <ul class="align-items-center">
                                            <li class="li"><a class="single-link"
                                                    href=" {{ route('perawatans.daftarperawatanalluser') }}">
                                                    Perawatan
                                                </a>
                                            </li>
                                            <li class="li"><a class="single-link"
                                                    href="{{ route('produks.daftarprodukalluser') }}">Produk</a>
                                            </li>
                                            <li class="li"><a class="single-link"
                                                    href=" {{ route('pakets.daftarpaketalluser') }}">Paket</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="single-wedge">

                                <h4 class="footer-herading">Informasi Salon.</h4>
                                <div class="footer-links">
                                    <!-- News letter area -->
                                    <p class="address">2008 Jl. Cempaka Indah <br>
                                        206, Surabaya - Jawa Timur</p>
                                    <p class="phone">Phone/Fax:<a href="tel:082198576905">082198576905</a></p>
                                    <p class="mail">Email:<a
                                            href="mailto:serenity160420050@gmail.com">serenity160420050@gmail.com</a>
                                    </p>


                                    <!-- News letter area  End -->
                                </div>
                            </div>
                        </div>
                        <!-- End single blog -->
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="copy-text"> © 2023 <strong>Serenity</strong> Made With <i class="fa fa-heart"
                                    aria-hidden="true"></i> By <a class="company-name" href="https://hasthemes.com/">
                                    <strong> K</strong></a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Area End -->


    <!-- Login Modal End -->
    <!-- Modal -->

    <!-- Modal end -->

    <!-- Global Vendor, plugins JS -->

    <!-- Vendor JS -->
    <script src="{{ asset('assets_pelanggan/js/vendor/jquery-3.5.1.min.js') }}"></script>

    <script src="{{ asset('assets_pelanggan/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/vendor/modernizr-3.11.2.min.js') }}"></script>

    <!--Plugins JS-->
    <script src="{{ asset('assets_pelanggan/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/countdown.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/scrollup.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/venobox.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/ajax-mail.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets_pelanggan/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets_pelanggan/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets_pelanggan/pages/datatables.init.js') }}"></script>

    <!-- Use the minified version files listed below for better performance and remove the files listed above -->
    <!-- <script src="{{ asset('assets_pelanggan/js/vendor/vendor.min.js') }}"></script>
    <script src="{{ asset('assets_pelanggan/js/plugins/plugins.min.js') }}"></script> -->

    <!-- Main Js -->
    <script src="{{ asset('assets_pelanggan/js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($(".errorLogin").val() == "errorLogin") {
                $("#loginActive").modal("show");
            }

            if ($(".errorRegister").val() == "errorRegister") {
                $("#registerActive").modal("show");
            }
        });

        $("body").on("click", "#btnModalLogin", function() {
            $("#loginActive").modal("show");
            $("#registerActive").modal("hide");
        });
    </script>
    @yield('javascript')
</body>

</html>
