<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Zegva - Responsive Admin & Dashboard Template | Themesdesign</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />

    <link rel="shortcut icon" href="{{ asset('assets_admin/images/favicon.ico') }}">

    <link href="{{ asset('assets_admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet">

    <link href="{{ asset('assets_admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets_admin/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets_admin/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets_admin/css/style.css') }}" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="accountbg"></div>

    <!-- Begin page -->
    <div class="home-btn d-none d-sm-block">
        <a href="{{ route("login") }}" class="text-white"><i class="mdi mdi-home h1"></i></a>
    </div>

    <div class="wrapper-page">

        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5">
                    <div class="card card-pages shadow-none mt-4">
                        <div class="card-body">
                            <div class="text-center mt-0 mb-3">
                                <a href="{{ route("login") }}" class="logo logo-admin">
                                    <img src="{{ asset('assets_admin/images/logo-dark.png') }}" class="mt-3"
                                        alt="" height="26"></a>
                                <p class="text-muted w-75 mx-auto mb-4 mt-4">Masukkan Username dan Password Anda yang
                                    telah terdaftar!</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close text-danger" data-dismiss="alert"
                                        aria-label="Close">
                                        <span class="text-danger" aria-hidden="true">&times;</span>
                                    </button>
                                    <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                                    @foreach ($errors->all() as $error)
                                        <p class="mt-0 mb-1">- {{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <form class="form-horizontal mt-4" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="col-12">
                                        <label class="font-wight-bold" for="username">Username</label>
                                        <input class="form-control" type="text" required id="username"
                                            placeholder="Masukkan Username" name="username" value="{{ old('username') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-12">
                                        <label class="font-wight-bold" for="password">Password</label>
                                        <input class="form-control" type="password" required id="password"
                                            placeholder="Masukkan Password" name="password">
                                    </div>
                                </div>

                                {{-- <div class="form-group">
                                    <div class="col-12">
                                        <div class="checkbox checkbox-primary">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1"> Remember
                                                    me</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="form-group text-center mt-3">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block waves-effect waves-light"
                                            type="submit">Log In</button>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-4">
                                    <div class="col-12">
                                        <div class="float-left">
                                            <a href="pages-recoverpw.html" class="text-muted"><i
                                                    class="fa fa-lock mr-1"></i>Lupa Password?</a>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('assets_admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets_admin/js/waves.min.js') }}"></script>

    <script src="{{ asset('assets_admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets_admin/js/app.js') }}"></script>

</body>

</html>

{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <p>Gagal</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
