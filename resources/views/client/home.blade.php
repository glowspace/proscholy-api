@extends('layout.client')

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-dark justify-content-between absolute-top">
        <div class="container">
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
    
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a href="#" class="btn"><img src="{{asset('img/logo_v2.png')}}" height="20"></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-search"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-book"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-user"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-info"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-plus"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-moon"></i></a>
        </div>
    </nav>
@endsection

{{-- @section('navbar')
    @include('client.components.menu_main')

    <div class="alert alert-primary"
         role="alert"
         style="margin: 12px; border-radius: 5px">
        <p>Vítejte v digitálním zpěvníku <b>ProScholy.cz</b>, který přichází na pomoc všem scholám, křesťanským kapelám,
            společenstvím a
            všem, kdo se chtějí modlit hudbou!</p>
        <hr>
        <p class="mb-0">Naše redakce teď pro vás připravuje první písně. Aktuálně je přidáno <b>{{$song_count}}</b>
            písní.</p>
    </div>

    @auth
        <a class="btn btn-secondary" href="{{route('admin.dashboard')}}">
            <i class="fas fa-users"></i> Administrace
        </a>
    @endauth --}}



    {{--<div style="margin-top: 20px">--}}
    {{--<div class="navbar-label material-shadow text-warning">Nejnavštěvovanější písně</div>--}}
    {{--@foreach($top_songs as $song_l)--}}
    {{--<a class="btn btn-secondary" href="{{route('client.song.text', $song_l)}}">--}}
    {{--<i class="fas fa-music"></i>{{$song_l->name}}</a>--}}
    {{--@endforeach--}}
    {{--</div>--}}
{{-- @endsection --}}

@section('content')
    <div class="background-home">
        <div class="container-fluid">
            <div class="logo-wrapper ">
                <div class="logo"></div>
                <span class="caption noselect">Zpěvník</span>
            </div>
            <div class="search-wrapper">
                <form method="POST"
                      action="{{route('client.search')}}">
                    @csrf
                    <input class="search-home"
                           name="query"
                           placeholder="Zadejte název písně (třeba Ať požehnán je Bůh)"
                           :autofocus="'autofocus'"
                           type="search">
                    <button type="submit"
                            class="search-submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
