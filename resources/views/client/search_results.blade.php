@extends('layout.client')

@section('navbar')
    @include('client.components.menu_main')
@endsection

@section('content')
    <Search str-prefill="{{ $search_string }}"></Search>
@endsection
