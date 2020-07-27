@extends('layout.admin')

@section('title-suffixed', 'Seznam autorů')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2">{{ $title ?? "Seznam autorů"}}</h1>
        {{-- @can('add authors')
            <a class="btn btn-outline-primary" href="{{route('admin.author.create')}}">+ Nový autor</a>
        @endcan --}}

        <authors-list></authors-list>
    </div>
@endsection

