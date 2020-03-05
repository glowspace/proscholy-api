@extends('layout.client-spa')

@push('head_links')
    {{-- preload the logo font --}}
    <link rel="preload" href="{{ asset('/fonts/cocogoose-pro-regular.ttf')}} " as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('/img/logo_bubble.svg') }}" as="image">
@endpush

@section('content')
    <client-spa></client-spa>
@endsection
