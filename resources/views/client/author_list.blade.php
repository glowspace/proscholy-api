@extends('layout.layout_old')

@section('content')

<div class="content-padding"></div>
    <h2>Abecední seznam autorů</h2>

    @foreach($authors as $author)
        <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
    @endforeach
</div>
@endsection
