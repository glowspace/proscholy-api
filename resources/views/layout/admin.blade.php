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
            <a class="btn btn-secondary" id="dark-mode-button" onclick="toggleDarkMode();" ><i class="fas fa-{{ (isset($_COOKIE['dark']) && $_COOKIE['dark'] == 'true') ? 'sun' : 'moon' }}"></i> {{ (isset($_COOKIE['dark']) && $_COOKIE['dark'] == 'true') ? 'Světlý' : 'Tmavý' }} režim</a>
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
    @if (isset($_COOKIE['dark']) && $_COOKIE['dark'] == 'true')
    <style id="darkStyle">
        .theme--light {opacity: 0; transition: .7s;}
        .theme--dark {opacity: 1; transition: .7s;}
    </style>
    <script>
        var dom_observer = new MutationObserver(function(mutation) {
            // this runs (multiple times but most importantly), before the body is rendered
            document.getElementsByTagName('body')[0].setAttribute('class', 'dark admin-dark');
        });
        var container = document.documentElement || document.body;
        var config = { attributes: true, childList: true, characterData: true };
        dom_observer.observe(container, config);
    </script>
    @endif
@endsection

@section('app-js')
    <script type="text/javascript" src="{{ mix('_admin/js/app.js') }}"></script>
@endsection

@push('scripts')
    <script>
        // mobile viewport soft keyboard fix
        setTimeout(function () {
            var viewheight = $(window).height();
            var viewwidth = $(window).width();
            var viewport = $("meta[name=viewport]");
            viewport.attr("content", "height=" + viewheight + "px, width=" +
                viewwidth + "px, initial-scale=1.0");
        }, 300);

        // progress bar
        if (!window.onbeforeunload) {
            window.onbeforeunload = function showProgressBar() {
                var bar = document.createElement('div');
                bar.className = 'fixed-top';
                bar.innerHTML = '<div role="progressbar" aria-valuemin="0" aria-valuemax="100" class="v-progress-linear m-0" style="height: 4px;"><div class="v-progress-linear__background info" style="height: 4px; opacity: 0.3; width: 100%;"></div><div class="v-progress-linear__bar"><div class="v-progress-linear__bar__indeterminate v-progress-linear__bar__indeterminate--active"><div class="v-progress-linear__bar__indeterminate long info"></div><div class="v-progress-linear__bar__indeterminate short info"></div></div></div></div>';
                document.getElementsByTagName('body')[0].appendChild(bar);
            };
        }

        // dark mode
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
        function toggleDarkMode(dontToggle) {
            let dark = (getCookie('dark') === 'true') ? true : false;
            if (!dontToggle) {
                dark = !dark;
                setCookie('dark', dark, 30);
            }
            if (dark) {
                document.getElementsByTagName('body')[0].setAttribute('class', 'dark admin-dark');
                document.getElementById('dark-mode-button').innerHTML = '<i class="fas fa-sun"></i> Světlý režim';
                VueApp.$root.dark = true;
            } else {
                document.getElementsByTagName('body')[0].removeAttribute('class');
                document.getElementById('dark-mode-button').innerHTML = '<i class="fas fa-moon"></i> Tmavý režim';
                VueApp.$root.dark = false;
            }
        }
        toggleDarkMode(true);
        if (document.getElementById('darkStyle')) {
            document.getElementById('darkStyle').remove();
        }
    </script>
@endpush
