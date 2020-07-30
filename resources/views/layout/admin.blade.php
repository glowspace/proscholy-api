@extends('layout.master')

@if(View::hasSection('title-edit'))
    @section('title')
    ● @yield('title-edit') – administrace ProScholy & Regenschori
    @endsection
@elseif(View::hasSection('title-suffixed'))
    @section('title')
    @yield('title-suffixed') – administrace ProScholy & Regenschori
    @endsection
@else
    @section('title', 'Administrace ProScholy & Regenschori')
@endif

@section('navbar')
    <nav class="navbar navbar-admin navbar-expand-lg navbar-dark justify-content-between absolute-top">
        <div>
            <a class="navbar-brand" href="{{route('admin.dashboard')}}"><img src="{{asset('img/logo_v2.png')}}" class="admin-logo">
                ProScholy & Regenschori
                <span style="color: #ffffff3d">– po ruce všem, kteří se chtějí modlit hudbou</span>
            </a>
            <a class="btn btn-secondary" id="dark-mode-button" onclick="toggleDarkMode();" >{{ $_COOKIE['dark'] == 'true' ? 'Světlý' : 'Tmavý' }} režim</a>
        </div>
    </nav>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <div class="sidebar bg-dark material-shadow">
                    <div class="overflow-hidden">
                        <a class="navbar-brand py-2" href="{{route('admin.dashboard')}}"><img src="{{asset('img/logo_v2.png')}}" class="admin-logo"></a>
                        @include('admin.components.menu')
                    </div>
                </div>
            </div>
            <div class="col-lg-10" style="position: static">
                @yield('content-withmenu')
            </div>
        </div>
    </div>
    <a class="btn btn-secondary mb-0 admin-report bg-transparent" target="_blank" title="Nahlásit chybu" href="https://slack.com/app_redirect?team=TCC9MSFQA&channel=CGHL024DD">
        <i class="fas fa-exclamation-triangle p-0"></i>
    </a>
@endsection

@section('app-css')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('_admin/css/admin.css') }}">
    @if ($_COOKIE['dark'] == 'true')
    <style>
        body {background-color: black;}
        .col-lg-10 {filter: invert(1);}
    </style>
    @endif
@endsection

@section('app-js')
    <script type="text/javascript" src="{{ mix('_admin/js/app.js') }}"></script>
@endsection

@push('scripts')
    <script>
        // Mobile viewport soft keyboard fix
        setTimeout(function () {
            var viewheight = $(window).height();
            var viewwidth = $(window).width();
            var viewport = $("meta[name=viewport]");
            viewport.attr("content", "height=" + viewheight + "px, width=" +
                viewwidth + "px, initial-scale=1.0");
        }, 300);

        function setCookie(cname,cvalue,exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires=" + d.toGMTString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function toggleDarkMode() {
            let dark = false;
            if (getCookie('dark') === 'true') {
                dark = true;
            }
            setCookie('dark', !dark, 30);
            checkDarkMode();
        }

        function checkDarkMode() {
            let dark = false;
            if (getCookie('dark') === 'true') {
                dark = true;
            }
            if (dark) {
                document.getElementsByTagName('body')[0].style.backgroundColor = 'black';
                document.getElementsByClassName('col-lg-10')[0].style.filter = 'invert(1)';
                document.getElementById('dark-mode-button').textContent = 'Světlý režim';
            } else {
                document.getElementsByTagName('body')[0].style.backgroundColor = '#f8fafc';
                document.getElementsByClassName('col-lg-10')[0].style.filter = 'invert(0)';
                document.getElementById('dark-mode-button').textContent = 'Tmavý režim';
            }
        }
        checkDarkMode();
    </script>
@endpush
