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
    <meta id="userToken" name="userToken" value="{{ Auth::check() ? Auth::user()->getApiToken() : ''}}">

    @stack('meta_tags')

    <title>
        @if(View::hasSection('title-edit'))

            ● @yield('title-edit') – Regenschori administrace

        @elseif(View::hasSection('title-suffixed'))

            @yield('title-suffixed') – Regenschori administrace

        @else
            Regenschori - administrace
        @endif
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Fonts awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    {{-- CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ mix('_admin/css/admin-ui.css') }}">
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

    @yield('google-analytics')

    @stack('head_links')
</head>
<body>
    <div id="app" class="@yield('wrapper-classes', 'page')">
        <nav class="navbar navbar-admin navbar-expand-lg navbar-light justify-content-between">
            <div>
                <a class="navbar-brand" href="{{route('admin.dashboard')}}"><img src="{{asset('img/logo_v2.png')}}" class="admin-logo">
                    Regenschori Administrace
                    <span class="navbar-motto">– po ruce všem, kteří se chtějí modlit hudbou</span>
                </a>
                <a class="btn btn-secondary" id="dark-mode-button" onclick="toggleDarkMode();" ><i class="fas fa-{{ (isset($_COOKIE['dark']) && $_COOKIE['dark'] == 'true') ? 'sun' : 'moon' }}"></i> {{ (isset($_COOKIE['dark']) && $_COOKIE['dark'] == 'true') ? 'Světlý' : 'Tmavý' }} režim</a>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2">
                    <div class="sidebar">
                        <div>
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

        {{-- Side navbar --}}
        @yield('sidebar')
    </div>

    {{-- Main JS built with Laravel's mix --}}
    <script type="text/javascript" src="{{ mix('_admin/js/app.js') }}"></script>

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
    @stack('scripts')
</body>
</html>



