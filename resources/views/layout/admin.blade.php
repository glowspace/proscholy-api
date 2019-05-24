@extends('layout.master')

{{-- @section('navbar-brand_href', route('admin.dashboard')) --}}

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-dark justify-content-between absolute-top">
        <div class="">
        <a class="navbar-brand" href="#"><img src="{{asset('img/logo_v2.png')}}" style="padding: 0 10px 0 0;" width="60">
            Zpěvník pro scholy
            <span style="color: #ffffff3d">- na pomoc všem, kteří se chtějí modlit hudbou</span>
        </a>
            {{-- <div>
                <a href="#" class="btn btn-secondary"><i class="fas fa-search"></i> Vyhledávání</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-book"></i> Zpěvníky</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-user"></i> Autoři písní</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-info"></i> O zpěvníku</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-plus"></i> Přidat píseň</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-moon"></i> Tmavý mód</a>
            </div> --}}
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
            <div class="col-lg-10">
                @yield("content-withmenu")
            </div>
        </div>
    </div>
@endsection

{{-- @section('sidebar')
    <div class="sidebar bg-dark material-shadow" id="navbarNav">
        @include('admin.components.menu')
    </div>
@endsection --}}

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
                .css({position: 'absolute'});
        }

        function hideNavbar() {
            navbarState = false;

            $('.sidebar').hide();
        }
    </script>
@endpush