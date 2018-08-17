@extends('layout')

@section('content')
    <h2>Zpěvník {{$translation->name}}</h2>

    <table class="table table-responsive">
        <tr>
            <td>{{$record->num}}</td>
            <td>{{$record->songTranslation->name}}</td>
        </tr>
    </table>

    @if($translation->authors->count() == 0)
        <i>Překlad nemá přiřazeného autora.</i>
    @elseif($translation->authors->count() == 1)
        Autor překladu: <a
                href="{{route('author.single', ['id'=> $translation->authors->first()->id])}}">{{$translation->authors->first()->name}}</a>
        <br>
    @else($translation->authors->count() > 1)
        Autoři překladu:<br>
        @foreach($translation->authors as $author)
            <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
        @endforeach
    @endif

    <p>Originál: <a href="{{route('song.single',['id'=>$translation->song->id])}}">{{$translation->song->name}}</a></p>

    @if(empty($translation->lyrics))
        <p><i>Nebyl přidán text.</i></p>
    @else
        <div style="color: black; font-size: 18px">
            {!! $translation->lyrics !!}
        </div>
    @endif

    <h4>Videa</h4>

    <div class="row">
        @if($translation->videos->count() == 0)
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($translation->videos->count() == 1)
            @foreach($translation->videos as $video)
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
        @elseif($translation->videos->count() == 2)
            @foreach($translation->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
            <div class="col-sm-4">
                <div class="card embed-responsive embed-responsive-16by9"></div>
            </div>
        @elseif($translation->videos->count() > 2)
            @foreach($translation->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
        @endif
    </div>



@endsection

