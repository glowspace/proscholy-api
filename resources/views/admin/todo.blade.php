@extends('layout')

@section('content')

    <a href="{{route('admin.dashboard')}}">Zpět do administrace</a>

    <h2>Materiály k doplnění</h2>
    {{--<a class="btn btn-info" href="{{route('admin.todo.random')}}">Zapnout režim doplňování</a>--}}

    <p>Toto je seznam částí zpěvníku, kde je potřeba doplnit dodátečné informace. Po doplnění daný záznam zmizí.</p>

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Videa bez štítku</b>
                    <span class="badge badge-warning badge-pill">{{$videos->count()}}</span>
                </li>
                @foreach($videos as $video)
                    <li class="list-group-item">
                        <a href="{{route('admin.video.edit',['id'=>$video->id])}}">{{$video->generateTitle()}}</a>
                    </li>
                @endforeach

            </ul>
        </div><div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Písně bez autora</b>
                    <span class="badge badge-warning badge-pill">{{$songs_w_author->count()}}</span>
                </li>
                @foreach($songs_w_author as $song)
                    <li class="list-group-item">
                        <a href="{{route('admin.song.author.add',['id'=>$song->id])}}">{{$song->name}}</a>
                    </li>
                @endforeach

            </ul>
        </div><div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Záznamy ve zpěvnících bez přiřazeného textu (překladu)</b>
                    <span class="badge badge-warning badge-pill">{{$songbook_record_w_translation->count()}}</span>
                </li>
                @foreach($songbook_record_w_translation as $record)
                    <li class="list-group-item">
                        <a href="{{route('admin.dashboard',['id'=>$record->id])}}">{{$record->generateTitle()}}</a>
                    </li>
                @endforeach

            </ul>
        </div><div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Písně s chybějícím textem</b>
                    <span class="badge badge-warning badge-pill">{{$song_lyrics_w_lyrics->count()}}</span>
                </li>
                @foreach($song_lyrics_w_lyrics as $translation)
                    <li class="list-group-item">
                        <a href="{{route('admin.song.edit',['id'=>$translation->id])}}">{{$translation->name}}</a>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

@endsection
