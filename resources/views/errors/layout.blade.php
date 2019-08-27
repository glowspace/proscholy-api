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
                <div class="text-center text-white pt-5">
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSfry7CQD0vPpuC_VB7xGR6NUF2WdPUytQwX8KipKoZcIYxbdA/viewform?usp=pp_url&entry.1025781741={{ urlencode(url()->full()) }}&entry.456507920=@yield('code')"
                        target="_blank"
                        class="btn btn-outline-light display-all-songs font-weight-bold">
                        <i class="fas fa-exclamation-triangle pr-1"></i> NAHLÁSIT
                    </a>
                </div>
			</div>
        </div>
    </div>
@endsection