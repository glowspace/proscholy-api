@extends('layout.layout_old')

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



    @if($author->members()->count() > 0)
        Členové:<br>
        @foreach($author->members as $member)
            <a href="{{route('author.single', ['id'=> $member->id])}}">{{$member->name}}</a><br>
        @endforeach
    @endif



    @if($author->memberships->count() > 0)
        Skupiny:<br>

        @foreach($author->isMemberOf as $author)
            <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
        @endforeach
        <br>
    @endif


    @if($author->songOriginalLyrics()->count() > 0)
        Písně:<br>

        @foreach($author->songOriginalLyrics as $translation)
            <a href="{{route('song_lyrics.single', ['id'=> $translation->id])}}">{{$translation->name}} </a><br>
        @endforeach
    @endif

    @if($author->songNotOriginalLyrics()->count() > 0)
        Překlady:<br>

        @foreach($author->songNotOriginalLyrics as $translation)
            <a href="{{route('song_lyrics.single', ['id'=> $translation->id])}}">{{$translation->name}} </a>
            (<a href="{{route('song_lyrics.single',['id'=>$translation->song->getOriginalLyric()->id])}}">{{$translation->song->name}}</a>)
            <br>
        @endforeach
    @endif

    <h4>Videa</h4>

    <div class="row">
        @if($author->externals->count() == 0)
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($author->externals->count() == 1)
            @foreach($author->externals as $external)
                <div class="col-sm-4">
                    {!! $external->getHtml() !!}
                </div>
            @endforeach
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($author->externals->count() == 2)
            @foreach($author->externals as $external)
                <div class="col-sm-4">
                    {!! $external->getHtml() !!}
                </div>
            @endforeach
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($author->externals->count() > 2)
            @foreach($author->externals as $external)
                <div class="col-sm-4">
                    {!! $external->getHtml() !!}
                </div>
            @endforeach
        @endif
    </div>


@endsection