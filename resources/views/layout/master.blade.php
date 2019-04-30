<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="apple-touch-icon"
          sizes="180x180"
          href="{{asset('img/favicon/apple-touch-icon.png')}}">
    <link rel="icon"
          type="image/png"
          sizes="32x32"
          href="{{asset('img/favicon/favicon-32x32.png')}}">
    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="{{asset('img/favicon/favicon-16x16.png')}}">
    <link rel="manifest"
          href="{{asset('img/favicon/site.webmanifest')}}">
    <link rel="mask-icon"
          href="{{asset('img/favicon/safari-pinned-tab.svg')}}"
          color="#5bbad5">
    <meta name="msapplication-TileColor"
          content="#da532c">
    <meta name="theme-color"
          content="#292929">

    <title>
        @yield('title', 'ProScholy.cz - chytrý křesťanský zpěvník')
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600"
          rel="stylesheet"
          type="text/css">

    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">

    <!-- Fonts awesome -->
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
          crossorigin="anonymous">

    {{-- CSS --}}
    <link rel="stylesheet"
          type="text/css"
          href="{{mix('css/app.css')}}">

    <!-- Google Analytics -->
    <script async
            src="https://www.googletagmanager.com/gtag/js?id=UA-94042414-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-94042414-8');
    </script>


    @stack('head_links')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark static-top justify-content-between fixed-top">
        <div>
            <a class="navbar-brand"
               href="@yield('navbar-brand_href', '/')"><img src="{{asset('img/logo_v2.png')}}"
                                                            width="60"
                                                            style="padding: 0 10px 0 0;">
                Zpěvník pro scholy</a>
        </div>

        @if (Auth::check())
            <a class="navbar-text"
               href="{{route('admin.dashboard')}}">
                Přihlášený uživatel: {{ Auth::user()->name }}
                @if (Auth::user()->roles()->count() > 0)
                    ({{Auth::user()->roles()->first()->name}})
                @endif
            </a>
        @endif

        <button class="navbar-toggler"
                type="button"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
                onclick="toggleNavbar()">
            <span class="navbar-toggler-icon"></span>
        </button>

    </nav>

    <div class="container-fluid"
         id="app">
        <div class="row">
            {{-- Side navbar --}}
            <div class="sidebar bg-dark material-shadow"
                 id="navbarNav">
                @yield('navbar')
            </div>

            {{-- Content --}}
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Main JS built with Laravel's mix --}}
    <script type="text/javascript"
            src="{{mix('js/app.js')}}"></script>

    <script>
        // Mobile viewport soft keyboard fix
        setTimeout(function () {
            var viewheight = $(window).height();
            var viewwidth = $(window).width();
            var viewport = $("meta[name=viewport]");
            viewport.attr("content", "height=" + viewheight + "px, width=" +
                viewwidth + "px, initial-scale=1.0");
        }, 300);


        // Navbar toggling
        let navbarState = false;

        function toggleNavbar() {
            console.log(navbarState);

            if (navbarState === false) {
                showNavbar();
            }
            else {
                hideNavbar();
            }
        }

        function showNavbar() {
            navbarState = true;

            $('.sidebar')
                .show()
                .css({position: 'fixed'});
        }

        function hideNavbar() {
            navbarState = false;

            $('.sidebar').hide();
        }
    </script>

    @stack('scripts')
</body>
</html>
