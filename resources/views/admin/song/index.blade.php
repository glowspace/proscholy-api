@extends('layout.admin')

@section('title-suffixed', 'Seznam písní')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2">{{ $title ?? "Seznam písní"}}</h1>

        <songs-list></songs-list>
    </div>
@endsection

