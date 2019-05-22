@extends('layout.admin')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam autorů"}}</h2>
        {{-- @can('add authors')
            <a class="btn btn-outline-primary" href="{{route('admin.author.create')}}">+ Nový autor</a>
        @endcan --}}

        <authors-list></authors-list>
    </div>
@endsection

