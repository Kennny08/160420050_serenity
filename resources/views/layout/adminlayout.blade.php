<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>@yield('title')</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ asset('assets_admin/images/logo-serenity/serenity-logo-no-background.png') }}">

    <link href="{{ asset('assets_admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ asset('assets_admin/plugins/morris/morris.css') }}">

    <link href="{{ asset('assets_admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets_admin/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets_admin/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets_admin/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link href="{{ asset('assets_admin/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets_admin/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets_admin/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Plugins -->
    <link href="{{ asset('assets_admin/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets_admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets_admin/pluginss/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}"
        rel="stylesheet" />


</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="index.html" class="logo">
                    <img src="{{ asset('assets_admin/images/logo-serenity/serenity.png') }}" class="logo-lg mt-3"
                        alt="" height="80">
                    <img src="{{ asset('assets_admin/images/logo-serenity/serenity.png') }}" class="logo-sm"
                        alt="" height="50">
                </a>
            </div>

            <!-- Search input -->
            {{-- <div class="search-wrap" id="search-wrap">
                <div class="search-bar">
                    <input class="search-input" type="search" placeholder="Search" />
                    <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                        <i class="mdi mdi-close-circle"></i>
                    </a>
                </div>
            </div> --}}

            <nav class="navbar-custom">
                <ul class="navbar-right list-inline float-right mb-0">

                    {{-- <li class="list-inline-item dropdown notification-list d-none d-md-inline-block">
                        <a class="nav-link waves-effect toggle-search" href="#" data-target="#search-wrap">
                            <i class="fas fa-search noti-icon"></i>
                        </a>
                    </li> --}}

                    <!-- language-->
                    {{-- <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ asset('assets_admin/images/flags/us_flag.jpg') }}" class="mr-2"
                                height="12" alt="" /> English <span class="mdi mdi-chevron-down"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated language-switch">
                            <a class="dropdown-item" href="#"><img
                                    src="{{ asset('assets_admin/images/flags/french_flag.jpg') }}" alt=""
                                    height="16" /><span> French </span></a>
                            <a class="dropdown-item" href="#"><img
                                    src="{{ asset('assets_admin/images/flags/spain_flag.jpg') }}" alt=""
                                    height="16" /><span> Spanish </span></a>
                            <a class="dropdown-item" href="#"><img
                                    src="{{ asset('assets_admin/images/flags/russia_flag.jpg') }}" alt=""
                                    height="16" /><span> Russian </span></a>
                            <a class="dropdown-item" href="#"><img
                                    src="{{ asset('assets_admin/images/flags/germany_flag.jpg') }}" alt=""
                                    height="16" /><span> German </span></a>
                            <a class="dropdown-item" href="#"><img
                                    src="{{ asset('assets_admin/images/flags/italy_flag.jpg') }}" alt=""
                                    height="16" /><span> Italian </span></a>
                        </div>
                    </li> --}}

                    <!-- full screen -->
                    <li class="dropdown notification-list list-inline-item d-none d-md-inline-block mr-3 align-middle">
                        <div>
                            @if (Auth::user()->role == 'admin')
                                <h5> <span class="font-weight-normal">Welcome, </span><strong>Admin</strong> </h5>
                            @else
                                <h5><span class="font-weight-normal">Welcome, </span>
                                    <strong>{{ Auth::user()->karyawan->nama }}</strong>
                                </h5>
                            @endif

                        </div>
                    </li>

                    <!-- full screen -->
                    <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                        <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                            <i class="fas fa-expand noti-icon"></i>
                        </a>
                    </li>

                    <!-- notification -->
                    {{-- <li class="dropdown notification-list list-inline-item">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fas fa-bell noti-icon"></i>
                            <span class="badge badge-pill badge-danger noti-icon-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg px-1">
                            <!-- item-->
                            <h6 class="dropdown-item-text">
                                Notifications
                            </h6>
                            <div class="slimscroll notification-item-list">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                                    <p class="notify-details"><b>Your order is placed</b><span
                                            class="text-muted">Dummy text of the printing and typesetting
                                            industry.</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-danger"><i class="mdi mdi-message-text-outline"></i>
                                    </div>
                                    <p class="notify-details"><b>New Message received</b><span class="text-muted">You
                                            have 87 unread messages</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-info"><i class="mdi mdi-filter-outline"></i></div>
                                    <p class="notify-details"><b>Your item is shipped</b><span class="text-muted">It
                                            is a long established fact that a reader will</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-message-text-outline"></i>
                                    </div>
                                    <p class="notify-details"><b>New Message received</b><span class="text-muted">You
                                            have 87 unread messages</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-warning"><i class="mdi mdi-cart-outline"></i></div>
                                    <p class="notify-details"><b>Your order is placed</b><span
                                            class="text-muted">Dummy text of the printing and typesetting
                                            industry.</span></p>
                                </a>

                            </div>
                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item text-center notify-all text-primary">
                                View all <i class="fi-arrow-right"></i>
                            </a>
                        </div>
                    </li> --}}

                    <li class="dropdown notification-list list-inline-item">
                        <div class="dropdown notification-list nav-pro-img">
                            <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{ asset('assets_admin/images/users/user-1.jpg') }}" alt="user"
                                    class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                                <!-- item-->
                                <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle"></i>
                                    Profile</a>
                                {{-- <a class="dropdown-item" href="#"><i class="mdi mdi-wallet"></i> My Wallet</a>
                                <a class="dropdown-item d-block" href="#"><span
                                        class="badge badge-success float-right">11</span><i
                                        class="mdi mdi-settings"></i> Settings</a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline"></i>
                                    Lock screen</a> --}}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger btn waves-effect waves-light"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-power text-danger"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    {{-- <button type="submit" class="dropdown-item text-danger"></button> --}}

                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="list-inline menu-left mb-0">
                    <li class="float-left">
                        <button class="button-menu-mobile open-left waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                </ul>

            </nav>

        </div>
        <!-- Top Bar End -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">

                <!--- Sidemenu -->
                <div id="sidebar-menu" class="mt-4">
                    <!-- Left Menu Start -->
                    <ul class="metismenu" id="side-menu">
                        <li class="menu-title">Menu</li>

                        {{-- @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                            <li>
                                <a href="{{ route('reservasis.index') }}" class="waves-effect">
                                    <i class="mdi mdi-view-dashboard-outline"></i> <span> Dashboard </span>
                                </a>
                            </li>
                        @endif --}}

                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i
                                    class="mdi mdi-inbox-multiple-outline"></i><span>
                                    Master <span class="float-right menu-arrow"><i
                                            class="mdi mdi-chevron-right"></i></span> </span>
                            </a>
                            <ul class="submenu">


                                <li>
                                    <a href="{{ route('perawatans.index') }}" class="waves-effect">
                                        <i class="mdi mdi-content-cut"></i> <span> Perawatan </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(0);" class="waves-effect"><i
                                            class="mdi mdi-spray-bottle"></i><span>
                                            Produk <span class="float-right menu-arrow"><i
                                                    class="mdi mdi-chevron-right"></i></span> </span></a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('produks.index') }}">Daftar Produk</a></li>
                                        <li
                                            class="{{ request()->is('kategoris/create') || request()->is('kategoris/*/edit') ? ' mm-active' : '' }}">
                                            <a href="{{ route('kategoris.index') }}">Kategori</a>
                                        </li>
                                        <li><a href="{{ route('mereks.index') }}">Merek</a></li>
                                        <li><a href="{{ route('kondisis.index') }}">Kondisi</a></li>
                                        @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                            <li><a href="{{ route('riwayatpengambilanproduks.index') }}">Riwayat
                                                    Pengambilan
                                                    Produk</a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                <li>
                                    <a href="{{ route('pakets.index') }}" class="waves-effect">
                                        <i class="mdi mdi-package"></i> <span> Paket </span>
                                    </a>
                                </li>


                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <li>
                                        <a href="javascript:void(0);" class="waves-effect"><i
                                                class="mdi mdi-account-multiple-outline"></i><span>
                                                Karyawan <span class="float-right menu-arrow"><i
                                                        class="mdi mdi-chevron-right"></i></span> </span></a>
                                        <ul class="submenu">
                                            @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                                <li><a href="{{ route('karyawans.index') }}">Daftar Karyawan</a></li>
                                                @if (Auth::user()->role == 'admin')
                                                    <li><a href="{{ route('presensikehadirans.index') }}">Presensi
                                                            Karyawan</a>
                                                    </li>
                                                @else
                                                    <li><a
                                                            href="{{ route('karyawans.presensihariinikaryawansalon') }}">Presensi
                                                            Karyawan
                                                        </a>
                                                    </li>
                                                @endif

                                                <li><a href="{{ route('admin.presensikehadirans.riwayatpresensi') }}">Riwayat
                                                        Presensi Karyawan</a></li>
                                                <li><a
                                                        href="{{ route('admin.presensikehadirans.riwayatizinkehadiran') }}">Riwayat
                                                        Izin Karyawan</a></li>
                                                @if (Auth::user()->role == 'admin')
                                                    <li><a href="{{ route('admin.karyawans.indexkomisikaryawan') }}">Komisi
                                                            Karyawan</a></li>
                                                @else
                                                    <li><a href="{{ route('karyawans.indexkomisikaryawansalon') }}">Komisi
                                                            Karyawan</a></li>
                                                @endif

                                            @endif
                                        </ul>
                                    </li>
                                @endif


                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <li>
                                        <a href="{{ route('slotjams.index') }}" class="waves-effect">
                                            <i class="dripicons-clock"></i> <span> Slot Jam </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('suppliers.index') }}" class="waves-effect">
                                            <i class="mdi mdi-alpha-s-box-outline"></i> <span>Supplier</span>
                                        </a>
                                    </li>
                                @endif


                                <li>
                                    <a href="javascript:void(0);" class="waves-effect"><i
                                            class="mdi mdi-ticket-percent"></i><span>
                                            Diskon <span class="float-right menu-arrow"><i
                                                    class="mdi mdi-chevron-right"></i></span> </span></a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('diskons.index') }}">Daftar Diskon</a></li>
                                        <li><a href="{{ route('diskons.daftardiskonberlaku') }}">Daftar Diskon Sedang
                                                Berlaku</a></li>
                                        <li><a href="{{ route('diskons.daftardiskonselesai') }}">Daftar Diskon Telah
                                                Selesai</a></li>
                                    </ul>
                                </li>

                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <li>
                                        <a href="{{ route('pelanggans.admin.daftarpelanggan') }}"
                                            class="waves-effect">
                                            <i class="mdi mdi-account"></i> <span> Pelanggan </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('ulasans.admin.daftarulasan') }}" class="waves-effect">
                                            <i class="mdi mdi-comment-account"></i> <span>Ulasan</span>
                                        </a>
                                    </li>
                                @endif


                            </ul>
                        </li>

                        @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                            <li>
                                <a href="{{ route('pembelians.index') }}" class="waves-effect {{ request()->is('pembelians/create') ? ' mm-active' : '' }}">
                                    <i class="mdi mdi-inbox"></i> <span>Pembelian</span>
                                </a>
                            </li>
                        @endif 

                        @if (Auth::user()->role == 'karyawan')
                            @if (Auth::user()->karyawan->jenis_karyawan == 'pekerja salon')
                                <li>
                                    <a href="javascript:void(0);" class="waves-effect"><i
                                            class="mdi mdi-account-multiple-outline"></i><span>
                                            Presensi Karyawan <span class="float-right menu-arrow"><i
                                                    class="mdi mdi-chevron-right"></i></span> </span></a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('karyawans.presensihariinikaryawansalon') }}">Presensi
                                                Karyawan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('karyawans.riwayatpresensikaryawansalon') }}">Riwayat
                                                Presensi
                                                Karyawan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('karyawans.daftarizinkaryawansalon') }}">Izin
                                                Karyawan
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endif


                        <li >
                            <a href="javascript:void(0);" class="waves-effect {{ request()->is('salon/reservasi/admin/create') || request()->is('salon/reservasi/admin/selectstaf') || request()->is('salon/reservasi/admin/detailreservasi/*') ? ' mm-active' : '' }}"><i
                                    class="mdi mdi-playlist-edit"></i><span>
                                    Reservasi <span class="float-right menu-arrow"><i
                                            class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu ">
                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <li class="{{ request()->is('salon/reservasi/admin/create') || request()->is('salon/reservasi/admin/selectstaf') || request()->is('salon/reservasi/admin/detailreservasi/*')  ? ' mm-active' : '' }}"><a href="{{ route('reservasis.index') }}"> Reservasi Salon</a></li>
                                    <li><a href="{{ route('riwayatreservasis.index') }}">Riwayat Reservasi
                                            Perawatan</a></li>
                                @else
                                    {{-- Nanti diisi dengan menu karyawan salon untuk reservasi dirinya dan riwayat reservasi dirinya --}}

                                    <li><a href="{{ route('karyawans.daftarreservasi') }}"> Reservasi
                                            Salon</a>
                                    </li>
                                    <li><a href="{{ route('karyawans.daftarriwayatreservasi') }}">Riwayat Reservasi
                                            Karyawan</a>
                                    </li>
                                @endif
                            </ul>
                        </li>



                        @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                            <li>
                                <a href="javascript:void(0);" class="waves-effect {{ request()->is('salon/penjualan/admin/tambahproduk/*')  ? ' mm-active' : '' }}"><i
                                        class="mdi mdi-cash-multiple"></i><span>
                                        Penjualan <span class="float-right menu-arrow"><i
                                                class="mdi mdi-chevron-right"></i></span> </span></a>

                                <ul class="submenu">
                                    <li>
                                        <a href="{{ route('penjualans.index') }}">
                                            Daftar Penjualan Tanpa Reservasi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('penjualans.admin.riwayatpenjualan') }}">
                                            Daftar Riwayat
                                            Penjualan Tanpa Reservasi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('penjualans.admin.daftarpenjualankeseluruhan') }}">
                                            Daftar Riwayat
                                            Penjualan Keseluruhan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i
                                        class="mdi mdi-cash-multiple"></i><span>
                                        Penjualan <span class="float-right menu-arrow"><i
                                                class="mdi mdi-chevron-right"></i></span> </span></a>

                                <ul class="submenu">
                                    <li>
                                        <a href="{{ route('penjualans.karyawan.index') }}">
                                            Daftar Penjualan Tanpa Reservasi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('penjualans.karyawan.riwayatpenjualan') }}">
                                            Daftar Riwayat
                                            Penjualan Tanpa Reservasi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('penjualans.karyawan.daftarpenjualankeseluruhan') }}">
                                            Daftar Riwayat
                                            Penjualan Keseluruhan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (Auth::user()->role == 'karyawan')
                            @if (Auth::user()->karyawan->jenis_karyawan == 'pekerja salon')
                                <li>
                                    <a href="{{ route('karyawans.indexkomisikaryawansalon') }}" class="waves-effect">
                                        <i class="mdi mdi-cash"></i> <span>Komisi Karyawan</span>
                                    </a>
                                </li>
                            @endif
                        @endif

                        @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                            <li>
                                <a href="{{ route('admin.settingrekomendasiproduk') }}" class="waves-effect">
                                    <i class="mdi mdi-star-box"></i> <span> Rekomendasi Produk</span>
                                </a>
                            </li>
                        @endif



                        <li class="menu-title">Lainnya</li>

                        <li>
                            <a onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                class="waves-effect"><i class="mdi mdi-logout"></i>
                                <span> Logout </span>
                            </a>

                        </li>


                    </ul>

                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <div class="container-fluid">
                    @yield('admincontent')
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            <footer class="footer">
                © 2023 Serenity <span class="d-none d-sm-inline-block"> - Crafted with <i
                        class="mdi mdi-heart text-danger"></i> by K</span>.
            </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- jQuery  -->

    <script src="{{ asset('assets_admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets_admin/js/waves.min.js') }}"></script>

    <script src="{{ asset('assets_admin/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('assets_admin/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/raphael/raphael.min.js') }}"></script>

    <script src="{{ asset('assets_admin/pages/dashboard.init.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets_admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets_admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets_admin/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets_admin/pages/datatables.init.js') }}"></script>


    <!-- App js -->
    <script src="{{ asset('assets_admin/js/app.js') }}"></script>

    <!-- Plugins js -->
    <script src="{{ asset('assets_admin/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
    <!-- Plugins Init js -->
    <script src="{{ asset('assets_admin/pages/form-advanced.init.js') }}"></script>
    @yield('javascript')

</body>

</html>
