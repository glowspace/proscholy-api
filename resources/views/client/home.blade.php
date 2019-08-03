@extends('layout.client')

@section('wrapper-classes', 'home')

@push('head_links')
    {{-- preload the logo font --}}
    <link rel="preload" href="fonts/cocogoose-pro-regular.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="/img/logo_bubble.svg" as="image">
@endpush

@section('content')
    <div class="background-home">
        <div class="container">
            <Search></Search>
        </div>
    </div>
@endsection
