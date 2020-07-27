@extends('layout.master')

@section('title', 'Administrace ProScholy & Regenschori')

@section('navbar')
    <nav class="navbar navbar-admin navbar-expand-lg navbar-dark justify-content-between absolute-top">
        <div>
            <a class="navbar-brand" href="{{route('admin.dashboard')}}"><img src="{{asset('img/logo_v2.png')}}" class="admin-logo">
                ProScholy & Regenschori
                <span style="color: #ffffff3d">– po ruce všem, kteří se chtějí modlit hudbou</span>
            </a>
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
                @yield("content-withmenu")
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
@endpush
