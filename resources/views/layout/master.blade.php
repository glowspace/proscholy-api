<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#292929">
    <meta id="baseUrl" name="baseUrl" value="{{url('')}}">
    @if (Auth::check())
    {{-- warning - potentially dangerous, only provisional solution --}}
        <meta id="userToken" name="userToken" value="{{ Auth::user()->getApiToken() }}">
    @endif

    <title>
        @yield('title', 'ProScholy.cz – chytrý křesťanský zpěvník')
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Fonts awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    {{-- CSS --}}
    @yield('app-css')

    @yield('google-analytics')

    @stack('head_links')
</head>
<body class="@yield('body-classes', 'page')">
    @yield("navbar")

    {{-- <nav class="navbar navbar-expand-lg navbar-dark static-top justify-content-between fixed-top"> --}}
        
        {{-- <div>
            <a class="navbar-brand" href="@yield('navbar-brand_href', '/')"><img src="{{asset('img/logo_v2.png')}}" width="60" style="padding: 0 10px 0 0;">
             Zpěvník pro scholy</a>
        </div> --}}

        {{-- @if (Auth::check())
                <a class="navbar-text" href="{{route('admin.dashboard')}}">
                Přihlášený uživatel: {{ Auth::user()->name }}
                @if (Auth::user()->roles()->count() > 0)
                    ({{Auth::user()->roles()->first()->name}})
                @endif
            </a>
        @endif --}}

        {{-- <button class="navbar-toggler" type="button"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleNavbar()">
            <span class="navbar-toggler-icon"></span>
        </button> --}}

    {{-- </nav> --}}

    

    <div id="app">
        @yield('content')
        {{-- Side navbar --}}
        @yield('sidebar')
    </div>

    {{-- Main JS built with Laravel's mix --}}
    @yield('app-js')
    
    @stack('scripts')
</body>
</html>