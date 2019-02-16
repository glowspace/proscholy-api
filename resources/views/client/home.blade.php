@extends('layout.layout')

@section('navbar')
    <button type="button" class="btn btn-secondary">Vyhledávání</button>
    <button type="button" class="btn btn-secondary">Kdo jsme</button>
    <button type="button" class="btn btn-secondary">Facebook</button>
    <button type="button" class="btn btn-secondary">Instagram</button>
@endsection

@section('content')
    <div class="background-home">
        <div class="logo-wrapper">
            <div class="logo"></div>
            <span class="caption material-shadow-text">Zpěvník</span>
        </div>
        <div class="search-wrapper">
            <form method="POST" action="{{route('client.search')}}">
                @csrf
                <input class="search-home material-shadow" name="query"
                       placeholder="Zadejte název písně (třeba Ať požehnán je Bůh)" autofocus>
                <button type="submit" class="search-submit material-shadow">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>
@endsection
