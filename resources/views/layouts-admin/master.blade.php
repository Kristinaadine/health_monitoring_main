<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin {{$setting->where('key', 'website_name')->first()->value}} | @stack('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="author" />
    <link rel="shortcut icon" href="{{ asset('') }}assets/img/logo/{{$setting->where('key', 'website_logo')->first()->value}}">

    <!-- g fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500&display=swap" rel="stylesheet">
    <!-- g fonts style ends -->

    <!-- Vendor or 3rd party style -->

    <!-- material icons -->
    <link href="{{ asset('') }}assets-admin/assets/vendor/material-icons/material-icons.css" rel="stylesheet">
    <!-- flags icons -->
    <link href="{{ asset('') }}assets-admin/assets/vendor/flags/css/flag-icon.min.css" rel="stylesheet">
    <!-- daterange picker -->
    <link href="{{ asset('') }}assets-admin/assets/vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
    <!-- vector map -->
    <link href="{{ asset('') }}assets-admin/assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.css" rel="stylesheet">
    <!-- Toast message -->
    <link href="{{ asset('') }}assets-admin/assets/vendor/jquery-jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet">
    <!-- dataTables picker -->
    <link href="{{ asset('') }}assets-admin/assets/vendor/DataTables-1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('') }}assets-admin/assets/vendor/DataTables-1.10.18/css/responsive.dataTables.min.css" rel="stylesheet">

    <!-- Vendor or 3rd party style ends -->

    <!-- Customized template style mandatory -->
    <link href="{{ asset('') }}assets-admin/assets/css/style-pista-light.css" rel="stylesheet" id="stylelink">
    <!-- Customized template style ends -->
    @stack('css')
</head>

<!-- Body -->

<body class="template-bg sidemenu-open">
    @include('layouts-admin.partials.header')

    <!-- wrapper starts -->
    <div class="wrapper">
        <div class="content shadow-sm">
            <div class="container-fluid header-container">
                <div class="row header">
                    <div class="container-fluid " id="header-container">
                        <div class="row">
                            <!-- Header starts -->
                            <nav class="navbar col-12 navbar-expand ">
                                <button class="menu-btn btn btn-link btn-sm" type="button">
                                    <i class="material-icons">menu</i>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <!-- search starts -->
                                    {{-- <form class="form-inline search mr-auto">
                                        <input class="form-control form-control-sm" type="search" placeholder="Search"
                                            aria-label="Search">
                                        <button class="btn btn-link btn-sm" type="submit"><i
                                                class="material-icons">search</i></button>
                                    </form> --}}
                                    <!-- search ends -->

                                    <!-- large desktop market rates starts -->

                                    <!-- large desktop market rates ends -->

                                    <!-- icons dropwdowns starts -->
                                    <ul class="navbar-nav ml-auto">

                                        <!-- profile dropdown-->
                                        <li class="nav-item dropdown ml-0 ml-sm-4">
                                            <a class="nav-link dropdown-toggle profile-link" href="#"
                                                id="navbarDropdown6" role="button" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <figure class="rounded avatar avatar-30">
                                                    <img src="{{ asset('') }}assets/img/user.png" alt="">
                                                </figure>
                                                <div class="username-text ml-2 mr-2 d-none d-lg-inline-block">
                                                    <h6 class="username"><span>Welcome,</span>{{auth()->user()->name}}</h6>
                                                </div>
                                                <figure class="rounded avatar avatar-30 d-none d-md-inline-block">
                                                    <i class="material-icons">expand_more</i>
                                                </figure>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right w-300 pt-0 overflow-hidden"
                                                aria-labelledby="navbarDropdown6">
                                                <div class="position-relative text-center rounded py-5">
                                                    <div class="background">
                                                        <img src="{{asset('')}}assets-admin/assets/img/background-part.png" alt="">
                                                    </div>
                                                </div>
                                                <div class="text-center mb-3 top-60 z-2">
                                                    <figure class="avatar avatar-120 mx-auto shadow"><img
                                                            src="{{ asset('') }}assets/img/user.png" alt=""></figure>
                                                </div>
                                                <h5 class="text-center mb-0">{{auth()->user()->name}}</h5>
                                                {{-- <p class="text-center text-secondary fs13">{{auth()->user()->address ?? ''}}</p> --}}
                                                <a class="dropdown-item border-top" href="{{locale_route('administration.user.show', encrypt(auth()->user()->id))}}">
                                                    <div class="row">
                                                        <div class="col-auto align-self-center">
                                                            <i class="material-icons text-success">account_box</i>
                                                        </div>
                                                        <div class="col pl-0">
                                                            <p class="mb-0">My Profile</p>
                                                            <p class="small text-mute text-trucated">Update details and
                                                                information</p>
                                                        </div>
                                                        <div class="col-auto align-self-center text-right pl-0">
                                                            <i class="material-icons text-mute small">chevron_right</i>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item border-top" href="{{ locale_route('logout') }}"
                                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                    <form id="logout-form" action="{{ locale_route('logout') }}"
                                                        method="GET" class="d-none">
                                                        @csrf
                                                        @method('GET')
                                                    </form>
                                                    <div class="row">
                                                        <div class="col-auto align-self-center">
                                                            <i class="material-icons text-danger">exit_to_app</i>
                                                        </div>
                                                        <div class="col pl-0">
                                                            <p class="mb-0 text-danger">Logout</p>
                                                        </div>
                                                        <div class="col-auto align-self-center text-right pl-0">
                                                            <i
                                                                class="material-icons text-mute small text-danger">chevron_right</i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                    <!-- icons dropwdowns starts -->
                                </div>
                            </nav>
                            <!-- Header ends -->
                        </div>
                    </div>
                </div>
                @yield('header')
            </div>

            @yield('content')
        </div>

    </div>
    <!-- wrapper ends -->

    {{-- @include('layouts.partials.footer') --}}

    <script src="{{ asset('') }}assets-admin/assets/js/jquery-3.3.1.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/js/popper.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/cookie/jquery.cookie.js"></script>
    <!-- Global js ends -->

    <!-- Vendor or 3rd party js -->

    <!-- date range picker -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/daterangepicker-master/moment.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- Chart js -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/chartjs/Chart.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/chartjs/utils.js"></script>
    <!-- Circular progress js  -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/circle-progress/circle-progress.min.js"></script>
    <!-- Sparklines js  -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/sparklines/jquery.sparkline.min.js"></script>
    <!-- DataTables js  -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/DataTables-1.10.18/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/DataTables-1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <!-- vector maps js  -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/jquery-jvectormap/jquery-jvectormap.js"></script>
    <script src="{{ asset('') }}assets-admin/assets/vendor/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- vector maps js  -->
    <script src="{{ asset('') }}assets-admin/assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.js"></script>

    <!-- Vendor or 3rd party js ends -->

    <!-- Customized template js mandatory -->
    <script src="{{ asset('') }}assets-admin/assets/js/main.js"></script>
    <!-- Customized template js ends -->

    <!-- theme picker -->
    <script src="{{ asset('') }}assets-admin/assets/js/style-picker.js"></script>
    <!-- theme picker ends -->

    <!-- Customized page level js -->
    {{-- <script src="{{asset('')}}assets/js/production-dashboard.js"></script> --}}
    <script src="{{ asset('') }}assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="{{ asset('') }}assets/js/notify.js"></script>
    <script>
        @if (session()->has('success'))
            $.notify("<?= session()->get('success') ?>", "success");
        @endif

        @if (session()->has('error'))
            $.notify("<?= session()->get('error') ?>", "error");
        @endif
    </script>
    @stack('js')
</body>

<!-- Body ends -->

</html>
