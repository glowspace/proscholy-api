@extends('layout')

@section('content')
    <a class="btn btn-info" href="{{route('admin.todo.random')}}">Přeskočit a načíst něco jiného</a>
    <a class="btn btn-info" href="{{route('admin.todo')}}">Návrat na seznam</a>
    <hr>

    @if(isset($song->is_original))
        <h2>Překlad {!! $song->getLink() !!}</h2>

        <form method="get"
              action="{{route('admin.todo.setTranslationAuthor', ['author_id'=>'0', 'song_id'=>$song->id])}}">
            <input value="" placeholder="jméno nového autora" name="new_author">
            <input type="submit">
        </form>

        <p>Je potřeba zvolit autora písně (CTRL + F pro vyhledávání).</p>

        <div style="height: 600px; overflow-y: scroll;">
            @foreach($authors as $author)
                <a href="{{route('admin.todo.setSongAuthor',['author_id'=>$author->id, 'song_id'=>$song->id])}}">{{$author->name}}</a>
                <br>
            @endforeach
        </div>
    @else
        <h2>Píseň {!! $song->getLink() !!}</h2>

        <form method="get"
              action="{{route('admin.todo.setSongAuthor', ['author_id'=>'0', 'song_id'=>$song->id])}}">
            <input value="" placeholder="jméno nového autora" name="new_author">
            <input type="submit">
        </form>

        <p>Je potřeba zvolit autora písně (CTRL + F pro vyhledávání).</p>

        <div style="height: 600px; overflow-y: scroll;">
            @foreach($authors as $author)
                <a href="{{route('admin.todo.setSongAuthor',['author_id'=>$author->id, 'song_id'=>$song->id])}}">{{$author->name}}</a>
                <br>
            @endforeach
        </div>
    @endif

@endsection
