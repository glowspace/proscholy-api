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
    <br><br>

    @if(isset($author->description))
        <p>{{$author->description}}</p>
    @endif

    {{--Pokud je to skupina--}}
    @if($author->type >= 1)

        @if($author->members()->count() > 0)
            Členové:<br>
            @foreach($author->members as $member)
                <a href="{{route('author.single', ['id'=> $member->id])}}">{{$member->name}}</a><br>
            @endforeach
        @endif
    @endif


    @if($author->memberships->count() > 0)
        Skupiny:<br>

        @foreach($author->isMemberOf as $author)
            <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
        @endforeach
        <br>
    @endif


    @if($author->songLyrics()->count() > 0)
        Písně:<br>

        @foreach($author->songLyrics as $translation)
            @if($translation->is_original)
                <a href="{{route('song_lyrics.single', ['id'=> $translation->id])}}">{{$translation->name}} </a>
            @else
                <a href="{{route('song_lyrics.single', ['id'=> $translation->id])}}">{{$translation->name}} </a>
                (<a href="{{route('song_lyrics.single',['id'=>$translation->id])}}">{{$translation->song->name}}</a>)
                <br>
            @endif
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