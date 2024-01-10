<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="robots" content="index, follow" />
    <title id="titletitle">
        Serenity
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

                                @if (Auth::check())
                                    @if (Auth::user()->role == 'pelanggan')
                                        <li class="dropdown "><a href="#">Reservasi <i
                                                    class="pe-7s-angle-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('reservasis.pelanggan.create') }}">Buat
                                                        Reservasi</a>
                                                </li>
                                                <li><a
                                                        href="{{ route('reservasis.riwayatreservasispelanggan.index') }}">Riwayat
                                                        Reservasi</a></li>
                                            </ul>
                                        </li>
                                    @endif
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
                                <a href="login.html" class="header-action-btn login-btn h5" data-bs-toggle="modal"
                                    data-bs-target="#loginActive">Log In</a>
                            @endif



                            <!-- Single Wedge Start -->
                            <!-- Single Wedge End -->
                            <!-- Single Wedge Start -->
                            {{-- <a href="#offcanvas-wishlist" class="header-action-btn offcanvas-toggle">
                                <i class="pe-7s-like"></i>
                            </a> --}}
                            <!-- Single Wedge End -->
                            {{-- <a href="#offcanvas-cart"
                                class="header-action-btn header-action-btn-cart offcanvas-toggle pr-0">
                                <i class="pe-7s-shopbag"></i>
                                <span class="header-action-num">01</span> --}}
                            <!-- <span class="cart-amount">€30.00</span> -->
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

                    @if (Auth::check())
                        @if (Auth::user()->role == 'pelanggan')
                            <li><a href="#"><span class="menu-text">Reservasi</span></a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('reservasis.pelanggan.create') }}">Buat Reservasi</a></li>
                                    <li><a href="{{ route('reservasis.riwayatreservasispelanggan.index') }}">Riwayat
                                            Reservasi</a></li>
                                </ul>
                            </li>
                        @endif
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
                <!-- About Intro Area start-->
                <div class="about-intro-area">
                    <div class="container position-relative h-100 d-flex align-items-center">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="about-intro-content">
                                    <h2 class="title">Tentang Kami</h2>
                                    <p>Temukan setiap gaya dan penampilanmu melalui ketenangan yang dapat kamu rasakan
                                        secara ajaib. Segera buktikan sendiri hasil di salon kami. Kami tunggu reservasi
                                        dari Anda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="intro-right"
                            style="margin-bottom: 12px; border: 1px solid #273ed464; border-radius: 10px;">
                            <img style="border-radius: 10px;"
                                src="{{ asset('assets_admin/images/logo-serenity/serenity.png') }}" height="506"
                                class="intro-right-image">
                        </div>
                    </div>
                </div>

                <!-- About Intro Area End-->

                <!-- Shop Page End  -->
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

    <!-- Login Modal Start -->
    <div class="modal popup-login-style" id="loginActive">
        <button type="button" class="close-btn" data-bs-dismiss="modal"><span
                aria-hidden="true">&times;</span></button>
        <div class="modal-overlay">
            <div class="modal-dialog p-0" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="login-content">
                            <h2>Login</h2>
                            <h3>Login ke Akun Anda</h3>
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
                            @else
                                <input type="hidden" value="tidakLogin" class="errorLogin">
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="text" placeholder="Username" name="username"
                                    value="{{ old('username') }}" required>
                                <input type="password" placeholder="Password" name="password" required>
                                <div class="remember-forget-wrap">
                                    <div class="forget-wrap">
                                        <a href="{{ route('lupapassword') }}">Lupa Password?</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" style="width: 50%">Log in</button>
                                </div>

                                <div class="member-register">
                                    <p> Belum punya Akun? <a id="btnModalRegister" style="cursor: pointer;"
                                            href="{{ route('pelanggans.bukaregister') }}">
                                            Register Sekarang</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
