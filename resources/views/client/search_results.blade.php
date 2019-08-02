@extends('layout.client')

@section('wrapper-classes', 'home')

@section('content')
    <div class="background-home">
        <div class="container">
            <Search str-prefill="{{ $search_string }}"></Search>
        </div>
    </div>
@endsection
