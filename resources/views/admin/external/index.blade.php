@extends('layout.admin')

@section('title-suffixed', 'Seznam externích zdrojů')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2">{{ $title ?? "Seznam externích zdrojů"}}</h1>

        <externals-list></externals-list>
    </div>
@endsection

