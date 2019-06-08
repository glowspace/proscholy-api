@extends('layout.client')

@section('body-classes', 'home')

@section('navbar')
    @include('client.components.menu_main')
@endsection

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
