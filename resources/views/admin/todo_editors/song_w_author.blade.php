@extends('layout.layout_old')

@section('content')
    <a class="btn btn-info" href="{{route('admin.todo')}}">Návrat na seznam</a>
    <hr>

    <h2>Píseň <a href="{{ $song->getLink() }}">{{ $song->name }}</a></h2>

    <p>Je potřeba zvolit autora písně (CTRL + F pro vyhledávání) nebo přidat nového.</p>

    <form method="get"
          action="{{route('admin.todo.setSongAuthor', ['author_id'=>'0', 'song_id'=>$song->id])}}">
        <input value="" placeholder="jméno nového autora" name="new_author">
        <input type="submit">
    </form>

    <div style="height: 600px; overflow-y: scroll;">
        @foreach($authors as $author)
            <a href="{{route('admin.todo.setSongAuthor',['author_id'=>$author->id, 'song_id'=>$song->id])}}">{{$author->name}}</a>
            <br>
        @endforeach
    </div>
@endsection
