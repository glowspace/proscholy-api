@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>{{ $title ?? "Seznam souborů"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>

        @if ($type == "show-all")
            <files-list></files-list>
        @elseif ($type == 'show-todo')
            <files-list v-bind:is-todo="true"></files-list>
        @endif
    </div>
@endsection

