@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Materiály k doplnění</h2>
        {{--<a class="btn btn-info" href="{{route('admin.todo.random')}}">Zapnout režim doplňování</a>--}}

        <p>Toto je seznam částí zpěvníku, kde je potřeba doplnit dodátečné informace. Po doplnění daný záznam zmizí.</p>

        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <b>Externí odkazy bez písničky/autora</b>
                        <span class="badge badge-warning badge-pill">{{$externals->count()}}</span>
                    </div>
                    @foreach($externals as $external)
                        <a class="list-group-item"
                           href="{{route('admin.external.edit',['id'=>$external->id])}}">{{$external->generateTitle()}}</a>
                    @endforeach
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <b>Soubory bez písničky/autora</b>
                        <span class="badge badge-warning badge-pill">{{$files->count()}}</span>
                    </div>
                    @foreach($files as $file)
                        <a class="list-group-item"
                            href="{{route('admin.file.edit',['id'=>$file->id])}}">{{$file->getPublicName()}}</a>
                    @endforeach
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <b>Písně bez autora</b>
                        <span class="badge badge-warning badge-pill">{{$songs_w_author->count()}}</span>
                    </div>

                    <ul class="list-group">
                        @foreach($songs_w_author as $song)
                            <a class="list-group-item"
                               href="{{route('admin.song.edit',['song_lyric'=>$song->id])}}">{{$song->name}}</a>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <b>Písně s chybějícím textem</b>
                        <span class="badge badge-warning badge-pill">{{$song_lyrics_w_lyrics->count()}}</span>
                    </div>
                    @foreach($song_lyrics_w_lyrics as $translation)
                        <a class="list-group-item"
                           href="{{route('admin.song.edit',['id'=>$translation->id])}}">{{$translation->name}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
