@extends('layout.client')

@section('wrapper-classes', 'home')
@section('title', 'Stránka nenalezena – Zpěvník ProScholy.cz')

@push('head_links')
    {{-- preload the logo font --}}
    <link rel="preload" href="{{ asset('/fonts/cocogoose-pro-regular.ttf')}} " as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('/img/logo_bubble.svg') }}" as="image">
@endpush

@section('content')
    <div class="background-home">
        <div class="container">
            <div class="error">
				<h1>Error @yield('code')</h1>
				@yield('error-description')
			</div>
        </div>
    </div>
@endsection