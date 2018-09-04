@extends('layout')

@section('content')
    <h2 style="margin-bottom: 0">{{$author->name}}</h2>
    @if($author->type == 0)
        <span style="color: dimgrey">autor</span>
    @elseif($author->type == 1)
        <span style="color: dimgrey">hudební uskupení</span>
    @elseif($author->type == 2)
        <span style="color: dimgrey">schola</span>
    @elseif($author->type == 3)
        <span style="color: dimgrey">kapela</span>
    @elseif($author->type == 4)
        <span style="color: dimgrey">sbor</span>
    @endif

    <p>{{$author->description}}</p>

    {{--Pokud je to skupina--}}
    @if($author->type >= 1)

        @if($author->members->count() > 0)
            Členové:<br>
            @foreach($author->members as $member)
                <a href="{{route('author.single', ['id'=> $member->id])}}">{{$member->name}}</a><br>
            @endforeach
        @endif
    @endif

    {{--Jednotlivec--}}
    @if($author->type == 0)
        Skupiny:<br>
        @if($author->isMemberOf->count() == 0)
            <i>Nepatří k žadné skupině.</i>
        @else
            @foreach($author->isMemberOf as $author)
                <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
            @endforeach
        @endif
        <br>
    @endif


    @if($author->songs->count() > 0)
        Písně:<br>
        @foreach($author->songs as $song)
            <a href="{{route('song.single', ['id'=> $song->id])}}">{{$song->name}}</a><br>
        @endforeach
        <br>
    @endif


    @if($author->songLyrics->count() > 0)
        Překlady<br>

        @foreach($author->songLyrics as $translation)
            <a href="{{route('translation.single', ['id'=> $translation->id])}}">{{$translation->name}} </a>
            (<a href="{{route('song.single',['id'=>$translation->id])}}">{{$translation->song->name}}</a>)<br>
        @endforeach
    @endif

    <h4>Videa</h4>

    <div class="row">
        @if($author->videos->count() == 0)
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($author->videos->count() == 1)
            @foreach($author->videos as $video)
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
        @elseif($author->videos->count() == 2)
            @foreach($author->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($author->videos->count() > 2)
            @foreach($author->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
        @endif
    </div>


@endsection