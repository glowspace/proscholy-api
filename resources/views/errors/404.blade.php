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
				<h1>Error 404</h1>
				<p>Stránka nebyla nalezena.<br> Zkuste použít vyhledávání.</p>
				<div class="text-center text-white">
					<a href="/" class="btn btn-outline-light display-all-songs font-weight-bold">
						<i class="fas fa-search pr-1"></i> VYHLEDÁVÁNÍ
					</a>
				</div>
			</div>
        </div>
    </div>
@endsection