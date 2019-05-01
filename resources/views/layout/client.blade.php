@extends('layout.master')

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
@endsection

@section('app-js')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection