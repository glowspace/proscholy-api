@extends('layout.admin')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam písní"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>

        @if ($type == "list-all")
            <songs-list></songs-list>
        @elseif($type == "todo-lyrics")
            <songs-list v-bind:has-lyrics="false"></songs-list>
        @elseif($type == "todo-authors")
            <songs-list v-bind:has-authors="false"></songs-list>
        @elseif($type == "todo-chords")
            <songs-list v-bind:has-chords="false"></songs-list>
        @elseif($type == "todo-tags")
            <songs-list v-bind:has-tags="false"></songs-list>
        @endif
    </div>
@endsection

