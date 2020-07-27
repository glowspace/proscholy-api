@extends('layout.admin')

@section('title-suffixed', $title ?? 'Písně')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2 mb-3">{{ $title ?? 'Písně'}}</h1>
        <songs-list></songs-list>
    </div>
@endsection
