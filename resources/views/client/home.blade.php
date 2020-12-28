@extends('layout.client')

@section('wrapper-classes', 'home')

@push('head_links')
    {{-- preload the logo font --}}
    <link rel="preload" href="{{ asset('/fonts/cocogoose-pro-regular.ttf')}} " as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('/img/logo_bubble.svg') }}" as="image">
    <meta http-equiv="refresh" content="2;url=admin">
@endpush

@section('content')
    <div class="background-home">
        <div class="container">
            <Search></Search>
        </div>
    </div>
@endsection
