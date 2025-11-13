<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="icon" type="image/png" href="{{ asset('') }}assets/img/logo/{{$setting->where('key', 'website_logo')->first()->value}}">
      <title>{{$setting->where('key', 'website_name')->first()->value}}</title>
      <!-- Slick Slider -->
      <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendor/slick/slick.min.css"/>
      <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendor/slick/slick-theme.min.css"/>
      <!-- Icofont Icon-->
      <link href="{{ asset('') }}assets/vendor/icons/icofont.min.css" rel="stylesheet" type="text/css">
      <!-- Bootstrap core CSS -->
      <link href="{{ asset('') }}assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Custom styles for this template -->
      <link href="{{ asset('') }}assets/css/style.css" rel="stylesheet">
      <!-- Sidebar CSS -->
      <link href="{{ asset('') }}assets/vendor/sidebar/demo.css" rel="stylesheet">
   </head>
   <body>
      <!-- Osahan Index -->
      <div class="osahan-index">
         <div class="bg-success d-flex align-items-center justify-content-center vh-100">
            <a  href="account-setup.html"><img class="index-osahan-logo" src="{{ asset('') }}assets/img/logo/{{$setting->where('key', 'website_logo')->first()->value}}" alt="">
            </a>
         </div>
      </div>

      <!-- Bootstrap core JavaScript -->
      <script src="{{ asset('') }}assets/vendor/jquery/jquery.min.js"></script>
      <script src="{{ asset('') }}assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- slick Slider JS-->
      <script type="text/javascript" src="{{ asset('') }}assets/vendor/slick/slick.min.js"></script>
      <!-- Sidebar JS-->
      <script type="text/javascript" src="{{ asset('') }}assets/vendor/sidebar/hc-offcanvas-nav.js"></script>
      <!-- Custom scripts for all pages-->
      <script src="{{ asset('') }}assets/js/osahan.js"></script>

      <script>
        setInterval(() => {
            window.location.replace('/home')
        }, 1000);
      </script>
   </body>
</html>
