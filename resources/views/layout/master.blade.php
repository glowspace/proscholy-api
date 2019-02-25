<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{mix('img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{mix('img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{mix('img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{mix('img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{mix('img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#292929">

    <title>
        @yield('title', 'ProScholy.cz - chytrý křesťanský zpěvník v ČR')
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Fonts awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/solid.css"
          integrity="sha384-wnAC7ln+XN0UKdcPvJvtqIH3jOjs9pnKnq9qX68ImXvOGz2JuFoEiCjT8jyZQX2z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/regular.css"
          integrity="sha384-zkhEzh7td0PG30vxQk1D9liRKeizzot4eqkJ8gB3/I+mZ1rjgQk+BSt2F6rT2c+I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css"
          integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/brands.css"
          integrity="sha384-nT8r1Kzllf71iZl81CdFzObMsaLOhqBU1JD2+XoAALbdtWaXDOlWOZTR4v1ktjPE" crossorigin="anonymous">

    {{-- CSS --}}
    <link rel="stylesheet" type="text/css" href="{{mix('css/app.css')}}">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94042414-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-94042414-8');
    </script>


    @stack('head_links')
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark static-top">
        <img src="{{mix('img/logo_v2.png')}}" width="60" style="padding: 0 10px 0 0">
        <a class="navbar-brand mr-1" href="@yield('navbar-brand_href', '/')"> Zpěvník pro scholy</a>
    </nav>

    <div class="container-fluid" id="app">
        <div class="row">
            <div class="d-none d-sm-block col-sm-4 col-md-3 col-lg-2 sidebar bg-dark material-shadow">
                @yield('navbar')
            </div>
            <div class="col-sm-8 col-md-9 col-lg-10 content">
                @yield('content')
            </div>
        </div>


    </div>
    {{-- Main JS built with Laravel's mix --}}
    <script type="text/javascript" src="{{mix('js/app.js')}}"></script>

    @stack('scripts')
</body>
</html>
