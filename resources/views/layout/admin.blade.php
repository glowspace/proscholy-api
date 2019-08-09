@extends('layout.master')

@section('navbar')
    <nav class="navbar navbar-admin navbar-expand-lg navbar-dark justify-content-between absolute-top">
        <div>
            <a class="navbar-brand" href="{{route('admin.dashboard')}}"><img src="{{asset('img/logo_v2.png')}}" style="padding: 0 10px 0 0;" width="60">
                Zpěvník pro scholy
                <span style="color: #ffffff3d">– po ruce všem, kteří se chtějí modlit hudbou</span>
            </a>
        </div>
    </nav>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <div class="sidebar bg-dark material-shadow" id="navbarNav">
                    @include('admin.components.menu')
                </div>
            </div>
            <div class="col-lg-10" style="position: static">
                @yield("content-withmenu")
            </div>
        </div>
    </div>
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