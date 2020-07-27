@extends('layout.admin')

@section('title-suffixed', 'Seznam souborů')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2">{{ $title ?? "Seznam souborů"}}</h1>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>

        @if ($type == "show-all")
            <files-list></files-list>
        @elseif ($type == 'show-todo')
            <files-list v-bind:is-todo="true"></files-list>
        @endif
    </div>
@endsection

