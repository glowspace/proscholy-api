@extends('layout.master')

@section('navbar')
    @include('client.components.menu_main')

    <div class="alert alert-primary" role="alert" style="margin: 12px; border-radius: 5px">
        <p>Vítejte v digitálním zpěvníku <b>ProScholy.cz</b>, který přichází na pomoc všem scholám, křesťanským kapelám,
            společenstvím a
            všem, kdo se chtějí modlit hudbou!</p>
        <hr>
        <p class="mb-0">Naše redakce teď pro vás připravuje první písně. Aktuálně je přidáno <b>{{$song_count}}</b>
            písní.</p>
    </div>

    {{--<div style="margin-top: 20px">--}}
        {{--<div class="navbar-label material-shadow text-warning">Nejnavštěvovanější písně</div>--}}
            {{--@foreach($top_songs as $song_l)--}}
                {{--<a class="btn btn-secondary" href="{{route('client.song.text', $song_l)}}">--}}
                    {{--<i class="fas fa-music"></i>{{$song_l->name}}</a>--}}
            {{--@endforeach--}}
    {{--</div>--}}
@endsection

@section('content')
    <div class="background-home">
        <div class="logo-wrapper ">
            <div class="logo"></div>
            <span class="caption noselect">Zpěvník</span>
        </div>
        <div class="search-wrapper">
            <form method="POST" action="{{route('client.search')}}">
                @csrf
                <input class="search-home" name="query"
                       placeholder="Zadejte název písně (třeba Ať požehnán je Bůh)" autofocus>
                <button type="submit" class="search-submit">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>
@endsection
