<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta content="Grecory, Dietary Journey, Healthy Monitoring, Healthy Monitoring App, Healthy Monitoring Mobile App"
        name="keywords">
    <meta content="Grecory, Dietary Journey, Healthy Monitoring, Healthy Monitoring App, Healthy Monitoring Mobile App"
        name="description" />
    <link rel="icon" type="image/png"
        href="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}">
    <title>{{ $setting->where('key', 'website_name')->first()->value }} - @stack('title')</title>
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendor/slick/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendor/slick/slick-theme.min.css" />
    <!-- Icofont Icon-->
    <link href="{{ asset('') }}assets/vendor/icons/icofont.min.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('') }}assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('') }}assets/css/style.css" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('') }}assets/vendor/sidebar/demo.css" rel="stylesheet">

    @stack('css')
</head>

<body class="fixed-bottom-padding">
    <div class="theme-switch-wrapper">
        <label class="theme-switch" for="checkbox">
            <input type="checkbox" id="checkbox" />
            <div class="slider round"></div>
            <i class="icofont-moon"></i>
        </label>
        <em>Enable Dark Mode!</em>
    </div>
    <!-- home page -->
    <div class="osahan-home-page">
        <div class="border-bottom p-3">
            <div class="title d-flex align-items-center">
                @yield('leading')
                <a class="toggle {{ (request()->segment(1) == 'growth-monitoring' && request()->segment(2) == '') || (request()->segment(1) == 'growth-detection' && request()->segment(2) == 'stunting' && request()->segment(3) == '') || (request()->segment(1) == 'growth-detection' && request()->segment(2) == 'list') ? '' : 'ms-auto' }} ms-3"
                    href="#"><i class="icofont-navigation-menu "></i></a>
            </div>
        </div>
        <!-- body -->
        <div class="osahan-body">
            @yield('content')
        </div>
    </div>
    <!-- Footer -->
    <div class="osahan-menu-fotter fixed-bottom bg-white text-center border-top">
        <div class="row m-0">
            <a href="{{ locale_route('home') }}"
                class="small col text-decoration-none p-2 {{ request()->segment(1) == 'home' ? 'text-dark font-weight-bold selected' : 'text-muted' }}">
                <p class="h5 m-0"><i
                        class="{{ request()->segment(1) == 'home' ? 'text-success' : '' }} icofont-home"></i></p>
                {{__('menu.Home')}}
            </a>
            <a href="{{ locale_route('food-guide') }}"
                class="text-muted col small text-decoration-none p-2 {{ request()->segment(1) == 'food-guide' ? 'text-dark font-weight-bold selected' : 'text-muted' }}">
                <p class="h5 m-0"><i
                        class="{{ request()->segment(1) == 'food-guide' ? 'text-success' : '' }} icofont-fruits"></i>
                </p>
                {{__('menu.FoodGuide')}}
            </a>
            <a href="{{ locale_route('meal-planner') }}"
                class="text-muted col small text-decoration-none p-2 {{ request()->segment(1) == 'meal-planner' ? 'text-dark font-weight-bold selected' : 'text-muted' }}">
                <p class="h5 m-0"><i
                        class="{{ request()->segment(1) == 'meal-planner' ? 'text-success' : '' }} icofont-food-basket"></i>
                </p>
               {{__('menu.MealPlanner')}}
            </a>
            <a href="{{ locale_route('profile') }}"
                class="small col text-decoration-none p-2 {{ request()->segment(1) == 'profile' ? 'text-dark font-weight-bold selected' : 'text-muted' }}">
                <p class="h5 m-0"><i
                        class="{{ request()->segment(1) == 'profile' ? 'text-success' : '' }} icofont-user"></i></p>
                {{__('menu.Profile')}}
            </a>
        </div>
    </div>
    <nav id="main-nav">
        <ul class="second-nav">
            <li class="text-center">
                <a href="{{ locale_route('profile') }}">
                    <div class="p-4 profile text-center">
                        <img src="{{ asset('') }}assets/img/user.png" class="img-fluid rounded-pill">
                        <h6 class="fw-bold m-0 mt-2">{{ auth()->user()->name }}</h6>
                        <p class="small text-muted">{{ auth()->user()->email }}</p>
                    </div>
                </a>
            </li>
            @php
                $currentPath = request()->path();
                if (Str::startsWith($currentPath, ['en/', 'id/'])) {
                    $currentPath = substr($currentPath, 3); // hapus prefix "en/" atau "id/"
                }
            @endphp
            <li class="{{ app()->getLocale() == 'id' ? 'active bg-secondary text-white' : '' }}">
                @if (app()->getLocale() == 'id')
                    <span class="d-block text-decoration-none text-white">
                        <i class="icofont-flag me-2"></i> Bahasa Indonesia
                    </span>
                @else
                    <a href="{{ url('id/' . $currentPath) }}" class="d-block text-decoration-none text-reset hover-bg-light">
                        <i class="icofont-flag me-2"></i> Bahasa Indonesia
                    </a>
                @endif
            </li>

            <li class="{{ app()->getLocale() == 'en' ? 'active bg-secondary text-white' : '' }}">
                @if (app()->getLocale() == 'en')
                    <span class="d-block text-decoration-none text-white">
                        <i class="icofont-flag me-2"></i> English
                    </span>
                @else
                    <a href="{{ url('en/' . $currentPath) }}" class="d-block text-decoration-none text-reset hover-bg-light">
                        <i class="icofont-flag me-2"></i> English
                    </a>
                @endif
            </li>
        </ul>
        <ul class="bottom-nav">
            <li class="email">
                <a class="text-danger" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <p class="h5 m-0"><i class="icofont-logout text-danger"></i></p>
                    {{ __('profile.logout') }}
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout', ['locale' => app()->getLocale()]) }}" method="GET" class="d-none">
                @csrf
                @method('GET')
            </form>
        </ul>
    </nav>
    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('') }}assets/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{ asset('') }}assets/vendor/slick/slick.min.js"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{ asset('') }}assets/vendor/sidebar/hc-offcanvas-nav.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('') }}assets/js/osahan.js"></script>
    <script src="{{ asset('') }}assets/js/notify.js"></script>
    <script src="{{ asset('') }}assets/js/sweet-alert/sweetalert.min.js"></script>
    <script>
        @if (session()->has('success'))
            $.notify("<?= session()->get('success') ?>", "success");
        @endif

        @if (session()->has('error'))
            $.notify("<?= session()->get('error') ?>", "error");
        @endif

        var $main_nav = $('#main-nav');
        var $toggle = $('.toggle');

        var defaultOptions = {
            disableAt: false,
            customToggle: $toggle,
            levelSpacing: 40,
            navTitle: " ",
            levelTitles: true,
            levelTitleAsBack: true,
            pushContent: '#container',
            insertClose: 2
        };

        // call our plugin
        var Nav = $main_nav.hcOffcanvasNav(defaultOptions);
    </script>
    @stack('js')
</body>

</html>