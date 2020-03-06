@extends('layout.master')

@section('navbar')
    @include('client.components.menu_main')
@endsection

@section('google-analytics')
     <!-- Google Analytics -->
     <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94042414-8"></script>
     <script>
         window.dataLayer = window.dataLayer || [];
 
         function gtag() {
             dataLayer.push(arguments);
         }
 
         gtag('js', new Date());
 
         gtag('config', 'UA-94042414-8');
     </script>
@endsection

@section('app-css')
    
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">

    <script type="text/javascript">
        // fix of flash of white background in Google Chrome when Dark mode is on... 
        
        var dom_observer = new MutationObserver(function(mutation) {
            // this runs (multiple times but most importantly), before the body is rendered
            if (window.localStorage) {
                if (window.matchMedia('(prefers-color-scheme: dark)') && localStorage.getItem("dark") === undefined) {
                    localStorage.setItem("dark", true);
                }

                if (localStorage.getItem("dark") === "true") {
                    document.getElementsByTagName("body")[0].className = "dark";
                }
            }
        });
        var container = document.documentElement || document.body;
        var config = { attributes: true, childList: true, characterData: true };
        dom_observer.observe(container, config);
    </script>
@endsection

@section('app-js')
    <script type="text/javascript" src="{{ mix('js/client.js') }}"></script>
@endsection