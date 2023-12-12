@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Beranda')

@section('pelanggancontent')


    <!-- Hero/Intro Slider Start -->
    <div class="section ">
        <div class="hero-slider swiper-container slider-nav-style-1 slider-dot-style-1">
            <!-- Hero slider Active -->
            <div class="swiper-wrapper">
                <!-- Single slider item -->
                <div class="hero-slide-item-2 slider-height swiper-slide d-flex bg-color1">
                    <div class="container align-self-center">
                        <div class="row">
                            <div class="col-xl-6 col-lg-5 col-md-5 col-sm-12 align-self-center sm-center-view">
                                <div class="hero-slide-content hero-slide-content-2 slider-animated-1">
                                    <span class="category">{{ $tanggalHariIni }}</span>
                                    <h2 class="title-1">Selamat Datang,<br> {{ Auth::user()->pelanggan->nama }}</h2>
                                    <a href="{{ route('reservasis.pelanggan.create') }}"
                                        class="btn btn-lg btn-primary btn-hover-dark">
                                        Reservasi Sekarang </a>
                                </div>
                            </div>
                            <div
                                class="col-xl-6 col-lg-7 col-md-7 col-sm-7 d-flex justify-content-center position-relative">
                                <div class="show-case">
                                    <div class="hero-slide-image">
                                        <img src="{{ asset('assets_pelanggan/images/beranda/salon_beranda2.jpeg') }}"
                                            alt="" class="img-fluid"
                                            style="max-height: 583px; width: 450px; max-width: 100%;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single slider item -->
                <div class="hero-slide-item-2 slider-height swiper-slide d-flex bg-color2">
                    <div class="container align-self-center">
                        <div class="row">
                            <div class="col-xl-6 col-lg-5 col-md-5 col-sm-5 align-self-center sm-center-view">
                                <div class="hero-slide-content hero-slide-content-2 slider-animated-1">
                                    <span class="category">{{ $tanggalHariIni }}</span>
                                    <h2 class="title-1">Selamat Datang,<br> {{ Auth::user()->pelanggan->nama }}</h2>
                                    <a href="{{ route('reservasis.pelanggan.create') }}"
                                        class="btn btn-lg btn-primary btn-hover-dark">
                                        Reservasi Sekarang </a>
                                </div>
                            </div>
                            <div
                                class="col-xl-6 col-lg-7 col-md-7 col-sm-7 d-flex justify-content-center position-relative">
                                <div class="show-case">
                                    <div class="hero-slide-image">
                                        <img src="{{ asset('assets_pelanggan/images/beranda/salon_beranda1.jpeg') }}"
                                            alt="" style="max-height: 583px; width: 450px; max-width: 100%;" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-white"></div>
            <!-- Add Arrows -->
            <div class="swiper-buttons">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>

    <!-- Hero/Intro Slider End -->

    <!-- Feature Area Srart -->
    <div class="feature-area  mt-n-65px">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <!-- single item -->
                    <div class="single-feature">
                        <div class="feature-icon" style="font-size: 30px;">
                            <i class="fa fa-list-alt text-white" aria-hidden="true"></i>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Total Reservasi Anda</h4>
                            <span class="sub-title">{{ $totalReservasi }} kali</span>
                        </div>
                    </div>
                </div>
                <!-- single item -->
                <div class="col-lg-4 col-md-6 mb-md-30px mb-lm-30px mt-lm-30px">
                    <div class="single-feature">
                        <div class="feature-icon" style="font-size: 30px;">
                            <i class="fa fa-scissors text-white" aria-hidden="true"></i>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Total Perawatan Anda</h4>
                            <span class="sub-title">{{ $totalPerawatan }} kali perawatan</span>
                        </div>
                    </div>
                </div>
                <!-- single item -->
                <div class="col-lg-4 col-md-6">
                    <div class="single-feature">
                        <div class="feature-icon" style="font-size: 30px;">
                            <i class="fa fa-shopping-bag text-white" aria-hidden="true"></i>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Total Produk Anda</h4>
                            <span class="sub-title">{{ $totalProduk }} produk dibeli</span>
                        </div>
                    </div>
                    <!-- single item -->
                </div>
            </div>
        </div>
    </div>
    <!-- Feature Area End -->


    <!-- Judul Perawatan Area Start -->
    <div class="product-area pt-100px pb-50px">
        <div class="container">
            <!-- Section Title & Tab Start -->
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-12">
                    <div class="section-title text-center mb-5">
                        <h2 class="title">#Perawatan</h2>

                    </div>
                </div>
                <!-- Section Title End -->
            </div>
            <!-- Section Title & Tab End -->

            <div class="row">
                <div class="col">
                    <div class="tab-content mb-30px0px">
                        <!-- 1st tab start -->
                        <div class="tab-pane fade show active" id="tab-product--all">
                            <div class="row">
                                @foreach ($perawatans as $perawatan)
                                    <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px" data-aos="fade-up"
                                        data-aos-delay="200">
                                        <!-- Single Prodect -->
                                        <div class="product">
                                            <div class="thumb">
                                                <a href="{{ route('perawatans.detailperawatanalluser', $perawatan->id) }}"
                                                    class="image text-center">
                                                    <img style="max-height: 100%; width: 260px; height: 310px; max-width: 100%; object-fit: cover;"
                                                        src="{{ asset('assets_admin/images/perawatan/' . $perawatan->gambar) }}"
                                                        alt="Product" />
                                                    <img style="max-height: 100%; width: 260px; height: 310px; max-width: 100%; object-fit: cover;"
                                                        class="hover-image"
                                                        src="{{ asset('assets_admin/images/perawatan/' . $perawatan->gambar) }}"
                                                        alt="Product" />
                                                </a>
                                                <a href="{{ route('perawatans.detailperawatanalluser', $perawatan->id) }}"
                                                    class=" add-to-cart">Detail</a>
                                            </div>
                                            <div class="content">
                                                <span class="ratings">
                                                    <span class="text-muted">(
                                                        {{ $penjualanPerawatans->where('perawatan_id', $perawatan->id)->count() }}
                                                        kali dilakukan
                                                        )</span>
                                                </span>
                                                <h5><a class="font-weight-bold text-dark"
                                                        href="{{ route('perawatans.detailperawatanalluser', $perawatan->id) }}">{{ $perawatan->nama }}
                                                    </a>
                                                </h5>
                                                <span class="price">
                                                    <span class="new">{{ $perawatan->durasi }} menit</span>
                                                </span>
                                                <span class="price">
                                                    <span class="new">Rp.
                                                        {{ number_format($perawatan->harga, 2, ',', '.') }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- 1st tab end -->

                    </div>
                    <a href="{{ route('perawatans.daftarperawatanalluser') }}"
                        class="btn btn-lg btn-primary btn-hover-dark m-auto"> Lihat Lebih
                        <i class="fa fa-arrow-right ml-15px" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Judul Perawatan Area End -->

    <!-- Produk Area Start -->
    <div class="product-area pt-100px pb-50px">
        <div class="container">
            <!-- Section Title & Tab Start -->
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-12">
                    <div class="section-title text-center mb-0">
                        <h2 class="title">#Produk</h2>
                        <!-- Tab Start -->
                        <div class="nav-center">

                            <ul class="product-tab-nav nav align-items-center justify-content-center">
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach ($produks as $produk)
                                    @if ($counter == 0)
                                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                                href="#tab-produk-{{ preg_replace('/[^a-zA-Z0-9\-_]/', '_', $produk['kategori']->nama) }}">{{ $produk['kategori']->nama }}</a>
                                        </li>
                                    @else
                                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                href="#tab-produk-{{ preg_replace('/[^a-zA-Z0-9\-_]/', '_', $produk['kategori']->nama) }}">{{ $produk['kategori']->nama }}</a>
                                        </li>
                                    @endif

                                    @php
                                        $counter += 1;
                                    @endphp
                                @endforeach
                            </ul>
                        </div>
                        <!-- Tab End -->
                    </div>
                </div>
                <!-- Section Title End -->
            </div>
            <!-- Section Title & Tab End -->

            <div class="row">
                <div class="col">
                    <div class="tab-content mb-30px0px">
                        @php
                            $counter = 0;
                        @endphp
                        @foreach ($produks as $produk)
                            @if ($counter == 0)
                                <div class="tab-pane fade show active"
                                    id="tab-produk-{{ preg_replace('/[^a-zA-Z0-9\-_]/', '_', $produk['kategori']->nama) }}">
                                    <div class="row">
                                        @foreach ($produk['produks'] as $produkPerKategori)
                                            <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px"
                                                data-aos="fade-up" data-aos-delay="200">
                                                <!-- Single Prodect -->
                                                <div class="product">
                                                    <div class="thumb">
                                                        <a href="{{ route('produks.detailprodukalluser', $produkPerKategori->id) }}"
                                                            class="image text-center">
                                                            <img style="max-height: 100%; width: 290px; height: 310px; max-width: 100%;"
                                                                src="{{ asset('assets_admin/images/produk/' . $produkPerKategori->gambar) }}"
                                                                alt="Product" />
                                                            <img style="max-height: 100%; width: 290px; height: 310px; max-width: 100%;"
                                                                class="hover-image"
                                                                src="{{ asset('assets_admin/images/produk/' . $produkPerKategori->gambar) }}"
                                                                alt="Product" />
                                                        </a>
                                                        <a href="{{ route('produks.detailprodukalluser', $produkPerKategori->id) }}"
                                                            class=" add-to-cart">Detail</a>
                                                    </div>
                                                    <div class="content">
                                                        <span class="ratings">
                                                            <span class="text-muted">
                                                                @php
                                                                    $totalKuantitasProduk = 0;
                                                                @endphp
                                                                @foreach ($penjualansSelesai as $penjualan)
                                                                    @php
                                                                        $totalKuantitasProduk += $penjualan->produks->where('id', $produkPerKategori->id)->sum('pivot.kuantitas');
                                                                    @endphp
                                                                @endforeach
                                                                ({{ $totalKuantitasProduk }}
                                                                telah terjual)
                                                            </span>
                                                        </span>
                                                        <h5 class="title"><a
                                                                href="{{ route('produks.detailprodukalluser', $produkPerKategori->id) }}">{{ $produkPerKategori->nama }}
                                                            </a>
                                                        </h5>
                                                        <span class="price">
                                                            <span
                                                                class="new">{{ number_format($produkPerKategori->harga_jual, 2, ',', '.') }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @else
                                <div class="tab-pane fade"
                                    id="tab-produk-{{ preg_replace('/[^a-zA-Z0-9\-_]/', '_', $produk['kategori']->nama) }}">
                                    <div class="row">
                                        @foreach ($produk['produks'] as $produkPerKategori)
                                            <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px "
                                                data-aos="fade-up" data-aos-delay="200">
                                                <!-- Single Prodect -->
                                                <div class="product">
                                                    <div class="thumb">
                                                        <a href="{{ route('produks.detailprodukalluser', $produkPerKategori->id) }}"
                                                            class="image text-center">
                                                            <img style="max-height: 100%; width: 290px; height: 310px; max-width: 100%;"
                                                                src="{{ asset('assets_admin/images/produk/' . $produkPerKategori->gambar) }}"
                                                                alt="Product" />
                                                            <img style="max-height: 100%; width: 290px; height: 310px; max-width: 100%;"
                                                                class="hover-image"
                                                                src="{{ asset('assets_admin/images/produk/' . $produkPerKategori->gambar) }}"
                                                                alt="Product" />
                                                        </a>
                                                        <a href="{{ route('produks.detailprodukalluser', $produkPerKategori->id) }}"
                                                            class=" add-to-cart">Detail</a>
                                                    </div>
                                                    <div class="content">
                                                        <span class="ratings">
                                                            <span class="text-muted">
                                                                @php
                                                                    $totalKuantitasProduk = 0;
                                                                @endphp
                                                                @foreach ($penjualansSelesai as $penjualan)
                                                                    @php
                                                                        $totalKuantitasProduk += $penjualan->produks->where('id', $produkPerKategori->id)->sum('pivot.kuantitas');
                                                                    @endphp
                                                                @endforeach
                                                                ({{ $totalKuantitasProduk }}
                                                                telah terjual)
                                                            </span>
                                                        </span>
                                                        <h5 class="title"><a
                                                                href="{{ route('produks.detailprodukalluser', $produkPerKategori->id) }}">{{ $produkPerKategori->nama }}
                                                            </a>
                                                        </h5>
                                                        <span class="price">
                                                            <span
                                                                class="new">{{ number_format($produkPerKategori->harga_jual, 2, ',', '.') }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endif
                            @php
                                $counter += 1;
                            @endphp
                        @endforeach
                        <!-- 1st tab start -->

                        <!-- 1st tab end -->

                    </div>
                    <a href="{{ route('produks.daftarprodukalluser') }}"
                        class="btn btn-lg btn-primary btn-hover-dark m-auto"> Lihat Lebih
                        <i class="fa fa-arrow-right ml-15px" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Produk Area End -->

    <!-- Judul Paket Area Start -->
    <div class="product-area pt-100px pb-50px">
        <div class="container">
            <!-- Section Title & Tab Start -->
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-12">
                    <div class="section-title text-center mb-5">
                        <h2 class="title">#Paket</h2>

                    </div>
                </div>
                <!-- Section Title End -->
            </div>
            <!-- Section Title & Tab End -->

            <div class="row">
                <div class="col">
                    <div class="tab-content mb-30px0px">
                        <!-- 1st tab start -->
                        <div class="tab-pane fade show active" id="tab-product--all">
                            <div class="row">
                                @foreach ($pakets as $paket)
                                    <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px" data-aos="fade-up"
                                        data-aos-delay="200">
                                        <!-- Single Prodect -->
                                        <div class="product">
                                            <div class="thumb">
                                                <a href="{{ route('pakets.detailpaketalluser', $paket->id) }}"
                                                    class="image">
                                                    <img style="max-height: 100%; width: 260px; height: 310px; max-width: 100%;"
                                                        src="{{ asset('assets_admin/images/paket/' . $paket->gambar) }}"
                                                        alt="Product" />
                                                    <img style="max-height: 100%; width: 260px; height: 310px; max-width: 100%;"
                                                        class="hover-image"
                                                        src="{{ asset('assets_admin/images/paket/' . $paket->gambar) }}"
                                                        alt="Product" />
                                                </a>
                                                <a href="{{ route('pakets.detailpaketalluser', $paket->id) }}"
                                                    class=" add-to-cart">Detail</a>
                                            </div>
                                            <div class="content">
                                                <span class="ratings">
                                                    <span class="text-muted">
                                                        @php
                                                            $totalDipilih = 0;
                                                        @endphp
                                                        @foreach ($penjualansSelesai as $penjualan)
                                                            @php
                                                                $totalDipilih += $penjualan->pakets->where('id', $paket->id)->count('pivot.jumlah');
                                                            @endphp
                                                        @endforeach

                                                        (
                                                        {{ $totalDipilih }}
                                                        kali dipilih
                                                        )
                                                    </span>
                                                </span>
                                                <h5><a class="font-weight-bold text-dark"
                                                        href="{{ route('pakets.detailpaketalluser', $paket->id) }}">{{ $paket->nama }}
                                                    </a>
                                                </h5>
                                                <span class="price">
                                                    <span class="new">Rp.
                                                        {{ number_format($paket->harga, 2, ',', '.') }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- 1st tab end -->

                    </div>
                    <a href=" {{ route('pakets.daftarpaketalluser') }}"
                        class="btn btn-lg btn-primary btn-hover-dark m-auto"> Lihat Lebih
                        <i class="fa fa-arrow-right ml-15px" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Judul Paket Area End -->


    <!-- Ulasan Area Start -->
    <div class="testimonial-area pt-100px pb-40px">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center mb-0">
                        <h2 class="title line-height-1">#Ulasan</h2>
                    </div>
                </div>
            </div>
            <!-- Slider Start -->
            <div class="testimonial-wrapper swiper-container">
                <div class="swiper-wrapper" style="display: flex; align-items: center;">
                    <!-- Slider Single Item -->
                    @foreach ($ulasans as $ulasan)
                        <div class="swiper-slide">
                            <div class="testi-inner">
                                <div class="reating-wrap">
                                    <span>{{ date('d-m-Y', strtotime($ulasan->created_at)) }}</span>
                                </div>
                                <div class="testi-content">
                                    <p>{{ $ulasan->ulasan }}
                                    </p>
                                </div>
                                <div class="testi-author">

                                    <div class="author-name">
                                        <h4 class="name">{{ $ulasan->penjualan->pelanggan->nama }}</h4>
                                        <span class="title">Happy Customer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Slider Start -->
        </div>
    </div>
    <!-- Ulasan Area End -->
@endsection

@section('javascript')
@endsection
