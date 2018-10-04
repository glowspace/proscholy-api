@extends('layout')

@section('content')
    <h2>{{$song_l->name}}</h2>

    @if($song_l->authors()->count() == 0)
        <i>Píseň nemá přiřazeného autora.</i>
    @else
        Autoři písně:<br>
        @foreach($song_l->authors as $author)
            <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
        @endforeach
    @endif

    {{-- <h4>Překlady:</h4>
    @foreach($song_l->translations as $translation)
        <a href="{{route('translation.single', ['id'=> $translation->id])}}">{{$translation->name}}</a><br>
    @endforeach

    @if($song_l->getOriginalTranslation() != null)
        <br><div style="font-family: 'Courier New', Courier, monospace !important;">
            {!! $song_l->getOriginalTranslation()->lyrics !!}
        </div>

    @endif --}}
{{-- 
    <p>Originál: <a href="{{route('song.single',['id'=>$translation->song->id])}}">{{$translation->song->name}}</a></p>

    @if(empty($translation->lyrics))
        <p><i>Nebyl přidán text.</i></p>
    @else
        <div style="color: black; font-size: 18px">
            {!! $translation->lyrics !!}
        </div>
    @endif --}}

    <h4>Videa</h4>

    <div class="row">
        @foreach($song_l->videos as $video)
            <div class="col-sm-4">
                {!! $video->getHtml() !!}
            </div>
        @endforeach
    </div>
@endsection
