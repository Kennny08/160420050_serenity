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
    <div class="error-bg"></div>

    <!-- Begin page -->
    <div class="home-btn d-none d-sm-block">
        <a href="index.html" class="text-white"><i class="mdi mdi-home h1"></i></a>
    </div>

    <div class="wrapper-page">

        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5">
                    <div class="card card-pages shadow-none mt-4">

                        <div class="text-center p-3">

                            <div class="img">
                                <img src="{{ asset('assets_admin/images/error.png') }}" class="img-fluid" alt="">
                            </div>

                            <h1 class="error-page mt-5"><span>404!</span></h1>
                            <h4 class="mb-4 mt-5">Sorry, page not found</h4>
                            <p class="mb-4 w-75 mx-auto">It will be as simple as Occidental in fact, it will Occidental
                                to an English person</p>
                            <a class="btn btn-primary mb-4 waves-effect waves-light" href="{{ route('reservasis.index') }}"><i
                                    class="mdi mdi-home"></i> Back to Dashboard</a>
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
