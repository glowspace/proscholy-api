@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-2 sidebar bg-dark material-shadow"></div>
        <div class="col-md-10 background-home">
            <div class="logo-wrapper">
                    <span class="caption">
                        <img class="logo-image" src="{{asset('img/logo_bubble.svg')}}" height="180"
                             alt="">Zpěvník</span>
            </div>
            <div class="search-wrapper">
                <input class="search-home  material-shadow"
                       placeholder="Zadejte název písně (třeba Ať požehnán je Bůh)">
                <input type="submit" class="search-submit">
            </div>
        </div>
    </div>
@endsection
