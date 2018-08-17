@extends('layout')

@section('content')
    <h2>{{$song->name}}</h2>

    @if($song->authors->count() == 0)
        <i>Píseň nemá přiřazeného autora.</i>
    @elseif($song->authors->count() == 1)
        Autor písně: <a
                href="{{route('author.single', ['id'=> $song->authors->first()->id])}}">{{$song->authors->first()->name}}</a>
        <br>
    @else($translation->authors->count() > 1)
        Autoři písně:<br>
        @foreach($song->authors as $author)
            <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
        @endforeach
    @endif

    <h4>Překlady:</h4>
    @foreach($song->translations as $translation)
        <a href="{{route('translation.single', ['id'=> $translation->id])}}">{{$translation->name}}</a><br>
    @endforeach

    @if($song->getOriginalTranslation() != null)
        <br><div style="font-family: 'Courier New', Courier, monospace !important;">
            {!! $song->getOriginalTranslation()->lyrics !!}
        </div>

    @endif

    <h4>Videa</h4>

    <div class="row">
        @if($song->getOriginalTranslation()->videos->count() == 0)
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($song->getOriginalTranslation()->videos->count() == 1)
            @foreach($song->getOriginalTranslation()->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($song->getOriginalTranslation()->videos->count() == 2)
            @foreach($song->getOriginalTranslation()->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($song->getOriginalTranslation()->videos->count() > 2)
            @foreach($song->getOriginalTranslation()->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
        @endif
    </div>
@endsection
