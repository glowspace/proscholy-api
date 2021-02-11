@extends('layout.master')

@push('head_links')
    <meta http-equiv="refresh" content="2;url=admin">
@endpush

@section('content')
    <div style="font-family:sans-serif;margin:1.5rem 2rem">
        <h1>Tady už nic není…</h1>
        <p>Stránka se přesměruje na <a href="admin" style="color:black">administraci</a>.</p>
    </div>
@endsection
